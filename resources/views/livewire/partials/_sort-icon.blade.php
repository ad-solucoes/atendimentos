<span style="display: inline-block;float:right">
@if($sortBy !== $field)
    <i class='text-muted fa fa-sort'></i>
@elseif($sortDirection == 'asc')
    <i style="color: #555555" class='fa fa-sort-amount-asc'></i>
@else
    <i style="color: #555555" class='fa fa-sort-amount-desc'></i>
@endif
</span>
