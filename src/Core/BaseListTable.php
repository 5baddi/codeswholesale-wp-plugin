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

namespace BaddiServices\CodesWholesale\Core;

use WP_List_Table;
use BaddiServices\CodesWholesale\Constants;

/**
 * Class BaseListTable.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
class BaseListTable extends WP_List_Table
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var array
     */
    private $hiddenFields = [];

    /**
     * @var int
     */
    private $itemsPerPage = Constants::PAGINATION_ITEMS_PER_PAGE;

    /**
     * @var array
     */
    private $sortableFields = [];

    public function prepare_items(): void
    {
        $this->_column_headers = [$this->get_columns(), $this->hiddenFields, $this->sortableFields];
        $this->items = array_slice($this->data, (($this->get_pagenum() - 1) * $this->itemsPerPage), $this->itemsPerPage);

        $this->set_pagination_args([
            'total_items' => sizeof($this->data),
            'per_page'    => $this->itemsPerPage,
            'total_pages' => ceil(sizeof($this->data) / $this->itemsPerPage),
        ]);
    }

    public function get_columns(): array 
    {
        return [];
    }

    public function column_default($item, $columnName)
    {
        return $item[$columnName] ?? null;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function setItemsPerPage(int $itemsPerPage = Constants::PAGINATION_ITEMS_PER_PAGE): self
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    public function setHiddenFields(array $fields): self
    {
        $this->hiddenFields = $fields;

        return $this;
    }

    public function setSortableFields(array $fields): self
    {
        $this->sortableFields = $fields;

        return $this;
    }
}