<?php

class Pagination {
    public $current_page;
    public $total_pages;
    public $item_per_page;

    public function __construct($current_page, $total_records, $item_per_page) {
        $this->current_page = $current_page;
        $this->item_per_page = $item_per_page;
        $this->total_pages = ceil($total_records / $item_per_page);
    }

    public function createUrl($page) {
        $queryParams = $_GET;
        $queryParams['pages'] = $page;
        $queryParams['per_page'] = $this->item_per_page;
        return '?' . http_build_query($queryParams);
    }

    public function render() {
        $output = '<div id="pagination">';
    
        // First page link
        if ($this->current_page > 3) {
            $output .= '<a class="page-item" href="' . $this->createUrl(1) . '">First</a>';
        }
    
        // Previous page link
        if ($this->current_page > 1) {
            $output .= '<a class="page-item" href="' . $this->createUrl($this->current_page - 1) . '">Prev</a>';
        }
    
        // Page number links
        for ($num = 1; $num <= $this->total_pages; $num++) {
            if ($num != $this->current_page) {
                if ($num > $this->current_page - 3 && $num < $this->current_page + 3) {
                    $output .= '<a class="page-item" href="' . $this->createUrl($num) . '">' . $num . '</a>';
                }
            } else {
                $output .= '<strong class="current-page page-item">' . $num . '</strong>';
            }
        }
    
        // Next page link
        if ($this->current_page < $this->total_pages) {
            $output .= '<a class="page-item" href="' . $this->createUrl($this->current_page + 1) . '">Next</a>';
        }
    
        // Last page link
        if ($this->current_page < $this->total_pages - 3) {
            $output .= '<a class="page-item" href="' . $this->createUrl($this->total_pages) . '">Last</a>';
        }
    
        $output .= '</div>';
    
        return $output;
    }
}
