<?php

namespace App\Http\Controllers\Staff;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Mirrors Java StaffController — @RequestMapping("/delivery")
 * Access enforced by Route::middleware(['auth', 'role:ROLE_STAFF,ROLE_ADMIN'])
 */
class StaffController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    // ── GET /delivery/dashboard ───────────────────────────────────────────────
    /** Java: @GetMapping("/dashboard") */
    public function dashboard(): View
    {
        $allOrders = $this->orderService->findAll();

        // Java: allOrders.stream().filter(o -> PROCESSING || DELIVERING)
        $processingOrders = $allOrders->filter(fn($o) =>
            in_array($o->status?->value ?? $o->status, [
                OrderStatus::PROCESSING->value,
                OrderStatus::DELIVERING->value,
            ])
        );

        return view('staff.dashboard', [
            'processingOrders' => $processingOrders,
            'orders'           => $allOrders,                                         // staff/dashboard uses orders.count()
            'totalOrders'      => $allOrders->count(),
            'pendingCount'     => $this->orderService->countByStatus(OrderStatus::PENDING),
            'processingCount'  => $this->orderService->countByStatus(OrderStatus::PROCESSING),
            'deliveringCount'  => $this->orderService->countByStatus(OrderStatus::DELIVERING),
            'doneCount'        => $this->orderService->countByStatus(OrderStatus::DONE),
            'cancelledCount'   => $this->orderService->countByStatus(OrderStatus::CANCELLED),
        ]);
    }

    // ── GET /delivery ─────────────────────────────────────────────────────────
    /** Java: @GetMapping("") — delivery board (excludes DONE and CANCELLED) */
    public function deliveryOrders(): View
    {
        $orders = $this->orderService->findAll()->reject(fn($o) =>
            in_array($o->status?->value ?? $o->status, [
                OrderStatus::DONE->value,
                OrderStatus::CANCELLED->value,
            ])
        );

        return view('staff.delivery', [
            'orders'   => $orders,
            'statuses' => OrderStatus::cases(),
        ]);
    }

    // ── GET /delivery/orders ──────────────────────────────────────────────────
    /** Java: @GetMapping("/orders") — all orders with optional status filter */
    public function allOrders(Request $request): View
    {
        $status = $request->input('status');

        return view('staff.orders', [
            'orders'        => $this->orderService->findAll($status ?: null),
            'statuses'      => OrderStatus::cases(),
            'currentStatus' => $status,
        ]);
    }

    // ── GET /delivery/order/{id} ──────────────────────────────────────────────
    /** Java: @GetMapping("/order/{id}") */
    public function orderDetail(int $id): View
    {
        return view('staff.order-detail', [
            'order'    => $this->orderService->findById($id),
            'statuses' => OrderStatus::cases(),
        ]);
    }

    // ── POST /delivery/order/{id}/status ─────────────────────────────────────
    /** Java: @PostMapping("/order/{id}/status") */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $request->validate(['status' => ['required', 'string']]);

        try {
            $this->orderService->updateStatus($id, $request->input('status'));
            return redirect()->route('delivery.order.show', $id)
                ->with('success', 'Cập nhật trạng thái thành công!');
        } catch (\Exception $e) {
            return redirect()->route('delivery.order.show', $id)
                ->with('error', $e->getMessage());
        }
    }
}
