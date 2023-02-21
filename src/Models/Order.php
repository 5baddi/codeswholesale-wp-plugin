<?php

/**
 * PHP version 7.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */

namespace BaddiServices\CodesWholesale\Models;

use Illuminate\Support\Arr;

/**
 * Class Order.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */

class Order
{
    public const CWS_ORDER_META_DATA = 'cws_order';
    public const CWS_ORDER_ID = 'orderId';
    public const CWS_ORDER_CLIENT_ORDER_ID = 'clientOrderId';
    public const CWS_ORDER_CREATED_ON = 'createdOn';
    public const CWS_ORDER_IDENTIFIER = 'identifier';
    public const CWS_ORDER_TOTAL_PRICE = 'totalPrice';
    public const CWS_ORDER_STATUS = 'status';
    public const CWS_ORDER_PRODUCTS = 'products';

    public const CWS_FIELDS = [
        self::CWS_ORDER_ID,
        self::CWS_ORDER_CLIENT_ORDER_ID,
        self::CWS_ORDER_CREATED_ON,
        self::CWS_ORDER_IDENTIFIER,
        self::CWS_ORDER_TOTAL_PRICE,
        self::CWS_ORDER_STATUS,
        self::CWS_ORDER_PRODUCTS,
    ];

    /**
     * @var string
    */
    private $id;

    /**
     * @var string
    */
    private $clientOrderId;

    /**
     * @var string
    */
    private $identifier;

    /**
     * @var string
    */
    private $status;

    /**
     * @var float
    */
    private $totalPrice;

    /**
     * @var int|null
    */
    private $createdOn;

    /**
     * @var array
    */
    private $products;

    public static function fromArray(array $attributes = []): self
    {
        $order = new self();

        foreach ($attributes as $key => $value) {
            if ($key === self::CWS_ORDER_ID) {
                $order->id = $value;

                continue;
            }

            $parsedValue = $value;

            switch ($key) {
                case self::CWS_ORDER_CREATED_ON:
                    $parsedValue = strtotime($value);

                    break;
                case self::CWS_ORDER_PRODUCTS:
                    $parsedValue = array_map(function ($item) {
                        $item['codes'] = array_map(function ($code) {
                            return Arr::except($code, ['links']);
                        }, $item['codes'] ?? []);

                        return Arr::except($item, ['links']);
                    }, $value);

                    break;
            }

            $order->{$key} = $parsedValue;
        }

        return $order;
    }

    public function toArray(bool $withCasting = true): array
    {
        $result = [];

        foreach (self::CWS_FIELDS as $key) {
            if ($key !== self::CWS_ORDER_ID && ! property_exists($this, $key)) {
                continue;
            }

            $parsedValue = $this->{$key};

            if ($key === self::CWS_ORDER_ID) {
                $parsedValue = $this->id;
            }

            if ($withCasting) {
                switch ($key) {
                    case self::CWS_ORDER_CREATED_ON:
                        $parsedValue = date('c', $this->createdOn);
    
                        break;
                }
            }

            $result[$key] = $parsedValue;
        }

        return $result;
    }

    public function __get(string $key)
    {
        if (! property_exists($this, $key)) {
            return null;
        }

        return $this->{$key};
    }

    public function __set(string $key, $value): void
    {
        if (! property_exists($this, $key)) {
            return;
        }

        $this->{$key} = $value;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}