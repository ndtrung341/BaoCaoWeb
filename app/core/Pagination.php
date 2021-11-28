<?php
class Pagination
{
   private $total;
   private $limit;
   private $currPage;

   function __construct($currPage, $total)
   {
      $this->currPage = $currPage;
      $this->total = $total;
   }

   function displayPageNumber()
   {
      $maxPages = 6;
      $start = $end = 0;
      if ($this->total <= $maxPages) {
         $start = 1;
         $end = $this->total;
      } else if ($this->currPage <= $maxPages - 2) {
         $start = 1;
         $end = $maxPages - 1;
      } else if ($this->currPage >= $this->total - ($maxPages - 2)) {
         $start = $this->total - ($maxPages - 2);
         $end = $this->total;
      } else {
         $start = $this->currPage;
         $end = $this->currPage + ($maxPages - 2) - 1;
      }
      $pages = range($start, $end);
      if ($start > 1) array_unshift($pages, 1, "•••");
      if ($end < $this->total) array_push($pages, "•••", $this->total);
      return $pages;
   }

   function renderPageLink($url)
   {
      $pages = $this->displayPageNumber();
      $html = '';
      // Render nút prev
      if ($this->currPage != 1) {
         $html .= '<a href="' . $url . '?page=' . ($this->currPage - 1) . '" class="page-control prev" data-page=' . $this->currPage - 1 . '>
                     <i class="fas fa-angle-left"></i>
                  </a>';
      }
      // Render danh sách trang
      foreach ($pages as $page) {
         if (!is_numeric($page)) {
            $html .= '<span class="ellipse">' . $page . '</span>';
         } else {
            if ($page == $this->currPage)
               $html .= '<span class="page active">' . $page . '</span>';
            else
               $html .= '<a href="' . $url . '?page=' . $page . '" class="page" data-page=' . $page . '>' . $page . '</a>';
         }
      }
      // Render nút next
      if ($this->currPage != $this->total) {
         $html .= '<a href="' . $url . '?page=' . ($this->currPage + 1) . '" class="page-control next" data-page=' . $this->currPage + 1 . '>
                     <i class="fas fa-angle-right"></i>
                  </a>';
      }
      return '<div class="pagination">' . $html . '</div>';
   }

   function renderBootstrap($url)
   {
      $pages = $this->displayPageNumber();
      $html = '';
      // Render nút prev
      if ($this->currPage != 1) {
         $html .= '<li class="page-item">
                     <a class="page-link" href="' . $url . '?page=' . ($this->currPage - 1) . '" aria-label="Previous">
                     <span aria-hidden="true">&laquo;</span>
                     </a>
                  </li>';
      }
      // Render danh sách trang
      foreach ($pages as $page) {
         if (!is_numeric($page)) {
            $html .= '<li class="page-item" aria-current="page">
                        <span class="page-link">' . $page . '</span>
                     </li>';
         } else {
            if ($page == $this->currPage)
               $html .= '<li class="page-item active" aria-current="page">
                           <span class="page-link">' . $page . '</span>
                        </li>';
            else
               $html .= '<li class="page-item">
                           <a href="' . $url . '?page=' . $page . '" class="page-link">' . $page . '</a>
                        </li>';
         }
      }
      // Render nút next
      if ($this->currPage != $this->total) {
         $html .= '<li class="page-item">
                     <a class="page-link" href="' . $url . '?page=' . ($this->currPage + 1) . '" aria-label="Previous">
                     <span aria-hidden="true">&raquo;</span>
                     </a>
                  </li>';
      }
      return '<ul class="pagination">' . $html . '</ul>';
   }

   function paginator($model, $whereClause = '', $whereArgs = [])
   {
      $result = $model->paginator($this->currPage, $this->limit, $whereClause, $whereArgs);
      $this->total = $result['totalPages'];
      return $result;
   }
}
