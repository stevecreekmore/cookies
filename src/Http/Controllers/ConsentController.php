<?php

namespace Stevecreekmore\Cookies\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stevecreekmore\Cookies\Services\ConsentLogger;

class ConsentController extends Controller
{
    public function __construct(
        protected ConsentLogger $logger
    ) {}

    /**
     * Log user consent
     */
    public function log(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'string',
            'action' => 'required|string|in:accept_all,reject_all,custom',
        ]);

        $log = $this->logger->log(
            $validated['categories'],
            $validated['action']
        );

        return response()->json([
            'success' => true,
            'logged' => $log !== null,
        ]);
    }

    /**
     * Withdraw consent
     */
    public function withdraw(Request $request): JsonResponse
    {
        $this->logger->log([], 'withdraw');

        return response()->json([
            'success' => true,
        ]);
    }
}
