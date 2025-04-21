<?php

namespace App\Controller;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends FrontendController
{
    #[Route('/products', name: 'product_list')]
    public function listAction(Request $request): Response
    {
        // Get filter parameters
        $page = $request->query->getInt('page', 1);
        $selectedCategories = $request->query->all('categories'); // Changed to use all() for array values
        $minPrice = $request->query->get('min_price');
        $maxPrice = $request->query->get('max_price');
        $sort = $request->query->get('sort', 'best_selling');
        $color = $request->query->get('color');
        $size = $request->query->get('size');
        $featured = $request->query->get('featured');
        $sale = $request->query->get('sale');
        $limit = 12;

        // Build product listing query
        $products = new Product\Listing();

        // Apply category filter
        if (!empty($selectedCategories)) {
            $products->addConditionParam('category IN (?)', [implode(',', $selectedCategories)]);
        }

        // Apply price filter
        if ($minPrice !== null) {
            $products->addConditionParam('price >= ?', [$minPrice]);
        }
        if ($maxPrice !== null) {
            $products->addConditionParam('price <= ?', [$maxPrice]);
        }

        // Apply featured filter
        if ($featured !== null) {
            $products->addConditionParam('featured = ?', [$featured]);
        }

        // Apply sale filter (this is a simplified approach - in a real app you might have a dedicated sale field)
        if ($sale !== null) {
            // For demo purposes, we'll just apply a filter to show some products
            // In a real app, you might have a dedicated 'sale' or 'discount' field
            // Here we're just showing products with a price less than 100 as being on sale
            $products->addConditionParam('price < ?', [100]);
        }

        // Apply color filter (in a real app, you might have a color field)
        if ($color !== null) {
            // For demo purposes, we'll just apply a filter based on the product name
            // In a real app, you would have a dedicated color field or relation
            $products->addConditionParam('name LIKE ?', ['%' . $color . '%']);
        }

        // Apply size filter (in a real app, you might have a size field)
        if ($size !== null) {
            // For demo purposes, we'll just apply a filter based on the product description
            // In a real app, you would have a dedicated size field or relation
            $products->addConditionParam('description LIKE ?', ['%' . $size . '%']);
        }

        // Apply sorting
        switch ($sort) {
            case 'price_asc':
                $products->setOrderKey('price');
                $products->setOrder('ASC');
                break;
            case 'price_desc':
                $products->setOrderKey('price');
                $products->setOrder('DESC');
                break;
            case 'newest':
                $products->setOrderKey('creationDate');
                $products->setOrder('DESC');
                break;
            case 'name_asc':
                $products->setOrderKey('name');
                $products->setOrder('ASC');
                break;
            case 'name_desc':
                $products->setOrderKey('name');
                $products->setOrder('DESC');
                break;
            default: // best_selling - in a real app, you might have a sales count field
                $products->setOrderKey('rating'); // Using rating as a proxy for best selling
                $products->setOrder('DESC');
        }

        // Apply pagination
        $products->setLimit($limit);
        $products->setOffset(($page - 1) * $limit);

        // Get all categories for filter
        $categories = new Category\Listing();
        $categories->setOrderKey('name');
        $categories->setOrder('asc');

        // Create an array to store categories with counts
        $categoriesWithCounts = [];

        foreach ($categories as $category) {
            // Count products in this category
            $categoryProducts = new Product\Listing();
            $categoryProducts->setCondition("category__id = ?", [$category->getId()]);
            $count = $categoryProducts->count();

            // Add to our array
            $categoriesWithCounts[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'count' => $count
            ];
        }

        // Create a copy of the listing for counting
        $countListing = clone $products;

        // Get total count for pagination
        $totalCount = $countListing->count();

        return $this->render('product/list.html.twig', [
            'products' => $products,
            'categories' => $categoriesWithCounts,
            'selectedCategories' => $selectedCategories,
            'currentPage' => $page,
            'totalPages' => ceil($totalCount / $limit),
            'currentSort' => $sort,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'color' => $color,
            'size' => $size,
            'featured' => $featured,
            'sale' => $sale
        ]);
    }

    #[Route('/product/{id}', name: 'product_detail')]
    public function detailAction(int $id): Response
    {
        $product = Product::getById($id);
        
        if (!$product) {
            throw new NotFoundHttpException('Product not found');
        }

        // Get related products from same category
        $relatedProducts = new Product\Listing();
        if ($product->getCategory()) {
            $relatedProducts->addConditionParam('category = ? AND id != ?', [$product->getCategory()->getId(), $product->getId()]);
            $relatedProducts->setLimit(4);
            $relatedProducts->setOrderKey('RAND()');
        }

        return $this->render('product/detail.html.twig', [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }

    #[Route('/cart/add', name: 'cart_add', methods: ['POST'])]
    public function addToCartAction(Request $request): JsonResponse
    {
        try {
            $this->validateCsrf('cart_add', $request->headers->get('X-CSRF-Token'));

            $data = json_decode($request->getContent(), true);
            $productId = $data['productId'] ?? null;
            $quantity = $data['quantity'] ?? 1;

            $product = Product::getById($productId);
            if (!$product) {
                throw new \Exception('Product not found');
            }

            if ($product->getQuantity() < $quantity) {
                throw new \Exception('Not enough stock available');
            }

            // Get cart from session
            $cart = $request->getSession()->get('cart', []);

            // Add or update product in cart
            if (isset($cart[$productId])) {
                $cart[$productId] += $quantity;
            } else {
                $cart[$productId] = $quantity;
            }

            // Update session
            $request->getSession()->set('cart', $cart);

            return new JsonResponse([
                'success' => true,
                'cartCount' => array_sum($cart),
                'message' => 'Product added to cart successfully'
            ]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
