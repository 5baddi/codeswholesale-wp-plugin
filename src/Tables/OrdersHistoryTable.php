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

use Illuminate\Support\Str;
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

    public function get_sortable_columns(): array
    {
        return [
            'identifier'    => ['identifier', true],
            'totalPrice'    => ['totalPrice', true],
            'status'        => ['status', true],
            'createdOn'     => ['createdOn', true],
        ];
    }

    public function column_default($item, $columnName)
    {
        $value = $item[$columnName] ?? null;

        switch ($columnName) {
            case 'totalPrice':
                $value = number_format($value, 2, '.', ' ');
                break;
            case 'createdOn':
                $value = date('Y/m/d H:i', strtotime($value));
                break;
        }

        return $value;
    }

    public function search(): self
    {
        $term = sanitize_text_field($_POST['s'] ?? '');
        if (empty($term)) {
            return parent::search();
        }

        $this->data = array_filter($this->data ?? [], function ($items) use ($term) {
            foreach ($items as $key => $item) {
                if (! in_array($key, array_keys($this->get_columns()))) {
                    continue;
                }

                $item = Str::lower((string) $item);
                if (Str::contains($item, Str::lower($term))) {
                    return true;
                }
            }
        });

        return parent::search();
    }
}