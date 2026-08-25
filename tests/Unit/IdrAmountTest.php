<?php

namespace Tests\Unit;

use App\Support\CashTender;
use App\Support\IdrAmount;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class IdrAmountTest extends TestCase
{
    public function test_parses_plain_and_dotted_rupiah(): void
    {
        $this->assertSame(100000, IdrAmount::parse('100000'));
        $this->assertSame(100000, IdrAmount::parse('100.000'));
        $this->assertSame(50000, IdrAmount::parse('Rp 50.000'));
        $this->assertSame(25000, IdrAmount::parse(25000));
        $this->assertNull(IdrAmount::parse(null));
        $this->assertNull(IdrAmount::parse(''));
    }

    public function test_cash_tender_computes_change_and_rejects_short_cash(): void
    {
        $this->assertSame([
            'cash_received' => 60000,
            'change_amount' => 4098,
        ], CashTender::resolve('cash', 55902, '60000'));

        $this->assertSame([
            'cash_received' => 100000,
            'change_amount' => 760,
        ], CashTender::resolve('cash', 99240, '100.000'));

        $this->assertSame([
            'cash_received' => 100000,
            'change_amount' => 33010,
        ], CashTender::resolve('cash', 66990, '100000'));

        $this->assertSame([
            'cash_received' => null,
            'change_amount' => null,
        ], CashTender::resolve('qris', 99240, '100.000'));

        $this->expectException(ValidationException::class);
        CashTender::resolve('cash', 9240, 5000);
    }
}
