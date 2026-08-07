<table>
    <tr><th>Sr.</th><th>Product Name</th><th>Category</th><th>Material</th></tr>
    <?php
    $srno=0;
    ?>
    @foreach($data as $data)
        <?php
        $srno++;
        ?>
        <tr><td>{{$srno}}</td>
            <td style="word-wrap:break-word"><x-product-name :row="$data" /></td>
            <td style="word-wrap:break-word">{{$data->category_name}}</td>
            <td style="word-wrap:break-word">{{$data->material_name}}</td></tr>
    @endforeach
</table>
