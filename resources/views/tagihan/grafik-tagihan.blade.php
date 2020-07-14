<div id="chartTagihan" style="height: 250px;"></div>

<script>
    new Morris.Area({
        fillOpacity : 1.0,
        behaveLikeLine : true,
        hideHover : 'auto',
        // ID of the element in which to draw the chart.
        element: 'chartTagihan',
        // Chart data records -- each entry in this array corresponds to a point on
        // the chart.
        data: [
            @forelse($data['paid'] as $key => $item)
                { 'bulan' : '{{$key}}', 'paid' : {{$item->sumnya}}, 'unpaid' : @if(isset($data['unpaid'][$key]->sumnya)) {{$data['unpaid'][$key]->sumnya}} @else 0 @endif },
            @empty
            @endforelse
        ],
        // The name of the data record attribute that contains x-values.
        xkey: 'bulan',
        // A list of names of data record attributes that contain y-values.
        ykeys: ['paid','unpaid'],
        // Labels for the ykeys -- will be displayed when you hover over the
        // chart.
        labels: ['dibayar','belum dibayar'],
        lineColors: ['#63acff','#ff5733']
    });
</script>
