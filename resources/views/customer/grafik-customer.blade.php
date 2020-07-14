<div id="chartCustomer" style="height: 250px;"></div>

<script>
    new Morris.Area({
        fillOpacity : 1.0,
        behaveLikeLine : true,
        hideHover : 'auto',
        // ID of the element in which to draw the chart.
        element: 'chartCustomer',
        // Chart data records -- each entry in this array corresponds to a point on
        // the chart.
        data: [
            @forelse($data as $key => $item)
                { 'tanggal' : '{{$key}}', 'customer' : {{$item->count()}} },
            @empty
            @endforelse
        ],
        // The name of the data record attribute that contains x-values.
        xkey: 'tanggal',
        // A list of names of data record attributes that contain y-values.
        ykeys: ['customer'],
        // Labels for the ykeys -- will be displayed when you hover over the
        // chart.
        labels: ['Pertambahan Jumlah Pelanggan'],
        lineColors: ['#63acff']
    });
</script>
