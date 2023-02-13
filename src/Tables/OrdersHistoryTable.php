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

namespace BaddiServices\CodesWholesale\Tables;

use BaddiServices\CodesWholesale\Core\BaseListTable;

/**
 * Class OrdersHistoryTable.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
class OrdersHistoryTable extends BaseListTable
{
    public function get_columns(): array
    {
        return [
            'identifier'    => cws5baddiTranslation('Order ID'),
            'totalPrice'    => cws5baddiTranslation('Total price (€)'),
            'status'        => cws5baddiTranslation('Status'),
            'createdOn'     => cws5baddiTranslation('Created on'),
            'actions'       => cws5baddiTranslation('Actions'),
        ];
    }

    public function column_default($item, $columnName)
    {
        $value = $item[$columnName] ?? null;

        switch ($columnName) {
            case 'totalPrice':
                $value = number_format(sprintf('%02f', $value), 2, '.', ' ');
                break;
            case 'createdOn':
                $value = date('Y/m/d H:i', strtotime($value));
                break;
        }

        return $value;
    }
}