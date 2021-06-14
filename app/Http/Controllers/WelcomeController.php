<?php

namespace App\Http\Controllers;

use App\Services\BalanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tochka\JsonRpcClient\Exceptions\ResponseException;

class WelcomeController extends Controller
{
    /** @var int */
    private const PAGE_LIMIT = 20;

    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request, BalanceService $balanceService)
    {
        $page = self::getPageParameter($request, 'page_history');

        $userId = $request->user()->id;

        $errors = [];
        $balance = null;
        try {
            $balance = $balanceService->getUsersBalance($userId);
        } catch (ResponseException $exception) {
            Log::error($exception);
            $errors[] = 'Error receiving user balance';
        }

        $history = null;
        try {
            $history = $balanceService->getBalanceHistory(self::PAGE_LIMIT, $page, $userId);
        } catch (ResponseException $exception) {
            Log::error($exception);
            $errors[] = 'Error receiving payment history';
        }

        list($nextLink, $previousLink) = array_values(self::getPaginateParams(
            $request,
            'page_history',
            $page,
            $history ? $history['total'] : 0
        ));

        return view('welcome', compact('balance', 'history', 'nextLink', 'previousLink'))
            ->with('errors', $errors);
    }

    /**
     * Get page parameter from request
     * @param \Illuminate\Http\Request $request
     * @param string $pageArgument
     * @return int
     */
    private static function getPageParameter(Request $request, string $pageArgument): int
    {
        $result = 1;
        $requestData = $request->validate([
            $pageArgument => 'integer',
        ]);

        if (array_key_exists($pageArgument, $requestData) && $requestData[$pageArgument] > 0) {
            $result = $requestData[$pageArgument];
        }

        return $result;
    }

    /**
     * Get urls for making pagination
     * @param \Illuminate\Http\Request $request
     * @param string $pageArgument
     * @param int $page
     * @param int $total
     * @return array
     *         - nextLink     string|null Url to next page
     *         - previousLink string|null Url to previous page
     */
    private static function getPaginateParams(Request $request, string $pageArgument, int $page, int $total): array
    {
        $result = [
            'nextLink' => null,
            'previousLink' => null,
        ];

        if ($total) {
            if ((self::PAGE_LIMIT * $page) < $total) {
                $result['nextLink'] = $request->fullUrlWithQuery([$pageArgument => $page + 1]);
            }

            if ($page > 1) {
                $result['previousLink'] = $request->fullUrlWithQuery([$pageArgument => $page - 1]);
            }
        }

        return $result;
    }
}
