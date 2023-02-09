<?php

function getPageItems($totalPages)
{
    $params = explode('&', $_SERVER['QUERY_STRING']);

    $currentPage = (int) str_replace('page=', '', $params[count($params) - 1]);


    if (str_contains($params[count($params) - 1], 'page')) {
        unset($params[count($params) - 1]);
        $currentParams = implode('&', $params);
    } else {
        $currentParams = $_SERVER['QUERY_STRING'];
    }


    $liString = '';
    if ($currentPage !== 1) {
        $prePage = $currentPage - 1;
        $liString .= "
            <li class='page-item'>
                <a class='page-link' href='?$currentParams&page=$prePage' aria-label='Previous'>
                    <span aria-hidden='true'>&laquo;</span>
                </a>
            </li>
        ";
    }

    for ($i = 1; $i <= $totalPages; $i++) {
        $liString .= "<li class='page-item'><a class='page-link' href='?$currentParams&page=$i'>$i</a></li>";
    }


    if ($currentPage !== $totalPages) {
        $nextPage = $currentPage + 1;
        $liString .= "
            <li class='page-item'>
                <a class='page-link' href='?$currentParams&page=$nextPage' aria-label='Next'>
                    <span aria-hidden='true'>&raquo;</span>
                </a>
            </li>
        ";
    }


    return $liString;
}

function Pagination($totalPages)
{
    $listPageItems = getPageItems($totalPages);

    echo "
    <nav aria-label='Page navigation example'>
        <ul class='pagination d-flex justify-content-center'>
            $listPageItems
        </ul>
    </nav> 
    ";
}
