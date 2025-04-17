function initialize() {


    // The context is a stack of subscribers. The top of the stack is the currently running subscriber.
    let context = [];


    // A signal is a pair of a getter and a setter.
    function createSignal(value) {
        let data = value;
        let subscriptions = new Set();

        const get = () => {
            const running = context[context.length - 1];
            if (running) {
                subscribe(running, subscriptions);
            }

            return data;
        };
        const set = (value) => {
            data = value;

            for (const subscription of [...subscriptions]) {
                subscription.execute();
            }
        }

        return [get, set];
    }

    // A subscriber is added to the dependencies of the currently running subscriber.
    function subscribe(running, subscriptions) {
        subscriptions.add(running);
        running.dependencies.add(subscriptions);
    }

    // Currently running subscriber is removed from the dependencies of all subscribers.
    // Also, all subscribers are removed from the dependencies of the currently running subscriber.
    function cleanup(running) {
        for (const dep of running.dependencies) {
            dep.delete(running);
        }
        running.dependencies.clear();
    }

    // An effect is a function that is executed when a signal changes.
    function createEffect(callback) {
        const execute = () => {
            cleanup(running);
            context.push(running);
            try {
                return callback();
            } finally {
                context.pop();
            }
        }

        const running= {
            dependencies: new Set(),
            execute
        }

        return execute();
    }

    // A memo is a signal that is only updated when its dependencies change.
    function createMemo(fn) {
        const [get, set] = createSignal(fn());
        createEffect(() => set(fn()));
        return get;
    }


    return {
        createSignal,
        createEffect,
        createMemo
    }

}

const {createSignal, createEffect, createMemo} = initialize();

export { createSignal, createEffect, createMemo};