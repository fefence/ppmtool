<html>
<head>
    <script type="text/css">
        th, td {
            padding: 5px;
            vertical-align: top;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
            margin: 0 auto;
        }
    </script>
</head>
<body>
<p>
    <span style="font-size:18px"><a href="{{$confirm_link}}" target="_blank">[confirm]</a></span><br><br>
</p>
@foreach($body as $match => $games)
{{$matches[$match]->home}} - {{$matches[$match]->away}} {{$matches[$match]->date_time}}
<table>
    @foreach($games as $type => $b)
    <tr>
        <td style="text-align: right;">[{{$type}}]</td>
        <td style="text-align: right;">[{{$b['length']}}]</td>
        <td style="text-align: right;">[BSF: {{$b['bsf']}}€]</td>
        <td style="font-size: 110%; font-weight: bold; text-align: right; color: darkred;">{{$b['bet']}}€</td>
        <td style="text-align: right;">@</td>
        <td style="font-size: 110%; font-weight: bold; text-align: right; color: darkred;">{{$b['odds']}}</td>
        <td style="text-align: right;">for</td>
        <td style="text-align: right;">{{$b['profit']}}€</td>
    </tr>
    @endforeach
</table>
@endforeach
<br>
<a href="{{$link_to_group}}" target="_blank">[view on bhapp.eu]</a>
</body>
</html>