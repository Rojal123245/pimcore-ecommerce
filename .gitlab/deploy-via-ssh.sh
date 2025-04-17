#!/bin/bash

set -e
# Update the package list and upgrade installed packages
echo "Updating system..."
apt-get update
apt-get upgrade -y

# Install OpenSSH Client

echo "Installing OpenSSH Client..."
apt-get install -y openssh-client

echo "OpenSSH: $(which ssh)"

# Verify installations
ssh -V

echo "Installing rsync..."
apt-get install -y rsync


SSH_DIR="$HOME/.ssh-test"

if [ ! -d "$SSH_DIR" ]; then
  mkdir -p "$SSH_DIR"
  chmod 700 "$SSH_DIR"
  echo "Created the $SSH_DIR directory."
else
  echo "The $SSH_DIR directory already exists."
fi

echo "Copying private key to server..."
echo "$SSH_PRIVATE_KEY" > "$SSH_DIR/deploy_key"
chmod 600 "$SSH_DIR/deploy_key"

SSH_RAW_COMMAND="ssh -o StrictHostKeyChecking=no -i $SSH_DIR/deploy_key -p $SSH_PORT"
SSH_COMMAND="$SSH_RAW_COMMAND $SSH_USER@$SSH_HOST"


echo "Creating deploy path if it does not exist..."
$SSH_COMMAND "mkdir -p $DEPLOY_PATH"

COMPOSER_COMMAND="$PHP_PATH $COMPOSER_PATH install --no-interaction --no-dev --optimize-autoloader --working-dir=$DEPLOY_PATH"
CONSOLE_COMMAND="$PHP_PATH $DEPLOY_PATH/bin/console"

if [ "$ENABLE_MAINTENANCE_MODE" = "1" ]; then
    echo "Enabling maintenance mode..."
    $SSH_COMMAND "$CONSOLE_COMMAND maintenance:enable"
fi



echo "Copying files to server..."
rsync -avze "$SSH_RAW_COMMAND" \
    --exclude ".env*" \
    --exclude ".git" \
    --exclude ".gitlab" \
    --exclude ".github" \
    --exclude ".vscode" \
    --exclude ".idea" \
    --exclude "tests" \
    --exclude "node_modules" \
    --exclude "vendor" \
    --exclude "cache" \
    --exclude ".htaccess" \
    -r ./ $SSH_USER@$SSH_HOST:$DEPLOY_PATH/

echo "Running composer install..."
$SSH_COMMAND $COMPOSER_COMMAND

echo "Installing assets..."
$SSH_COMMAND "$CONSOLE_COMMAND assets:install --symlink --relative --no-interaction --ignore-maintenance-mode"

echo "Running database migrations..."
$SSH_COMMAND "$CONSOLE_COMMAND doctrine:migrations:migrate --no-interaction --ignore-maintenance-mode"

echo "Building classes..."
$SSH_COMMAND "$CONSOLE_COMMAND pimcore:build:classes --no-interaction --ignore-maintenance-mode"

echo "Deploying class definitions..."
$SSH_COMMAND "$CONSOLE_COMMAND pimcore:deployment:classes-rebuild --create-classes --no-interaction --ignore-maintenance-mode"

if [ "$ENABLE_MAINTENANCE_MODE" = "1" ]; then
    echo "Disabling maintenance mode..."
    $SSH_COMMAND "$CONSOLE_COMMAND maintenance:disable --ignore-maintenance-mode"
fi

echo "Clearing cache..."
$SSH_COMMAND "$CONSOLE_COMMAND cache:clear"
$SSH_COMMAND "$CONSOLE_COMMAND pimcore:cache:clear"

echo "Deployment Completed!"
echo "Cleaning up.."
rm -rf $SSH_DIR
echo "Done!"