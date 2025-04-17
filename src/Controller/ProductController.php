<?php

namespace App\Controller;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

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
        $sort = $request->query->get('sort', 'name_asc');
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

        // Apply sorting
        switch ($sort) {
            case 'name_desc':
                $products->setOrderKey('name');
                $products->setOrder('DESC');
                break;
            case 'price_asc':
                $products->setOrderKey('price');
                $products->setOrder('ASC');
                break;
            case 'price_desc':
                $products->setOrderKey('price');
                $products->setOrder('DESC');
                break;
            case 'rating_desc':
                $products->setOrderKey('rating');
                $products->setOrder('DESC');
                break;
            default: // name_asc
                $products->setOrderKey('name');
                $products->setOrder('ASC');
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
            $products = new Product\Listing();
            $products->setCondition("category__id = ?", [$category->getId()]);
            $count = $products->count();
            
            // Add to our array
            $categoriesWithCounts[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'count' => $count
            ];
        }
        
        // Get total count for pagination
        $totalCount = $products->count();

        return $this->render('product/list.html.twig', [
            'products' => $products,
            'categories' => $categoriesWithCounts,
            'selectedCategories' => $selectedCategories,
            'currentPage' => $page,
            'totalPages' => ceil($totalCount / $limit),
            'currentSort' => $sort,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice
        ]);
    }

    #[Route('/product/{id}', name: 'product_detail')]
    public function detailAction(Product $product): Response
    {
        // Get related products from same category
        $relatedProducts = new Product\Listing();
        $relatedProducts->addConditionParam('category = ? AND id != ?', [$product->getCategory()->getId(), $product->getId()]);
        $relatedProducts->setLimit(4);
        $relatedProducts->setOrderKey('RAND()');

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
