@extends('layout')

@section('content')

<table class="table" id="matches" style="margin-bottom: 30px;">
    <thead>
    <tr>
        <th><input type="text" name="search_engine" class="search_init" placeholder="date"></th>
        <th><input type="text" name="search_engine" class="search_init" placeholder="country"></th>
        <th><input type="text" name="search_engine" class="search_init" placeholder="league"></th>
        <th><input type="text" name="search_engine" class="search_init" placeholder="type"></th>
        <th><input type="text" name="search_engine" class="search_init" placeholder="action"></th>
        <th><input type="text" name="search_engine" class="search_init" placeholder="description"></th>
    </tr>
    <tr>
        <th style="width: 140px;">date</th>
        <th style="width: 80px;">country</th>
        <th style="width: 40px;">l</th>
        <th style="width: 40px;">type</th>
        <th>action</th>
        <th>description</th>
    </tr>
    </thead>
    <tbody>
    @foreach($log as $d)
    <tr>
        <td>{{$d->created_at}}</td>
        <td>{{$d->country}}</td>
        <td>{{$d->country_alias}}</td>
        <td>{{$d->type}}</td>
        <td>{{$d->action}}</td>
        <td>{{$d->description}}</td>
    </tr>
    @endforeach

    </tbody>
</table>
<script type="text/javascript">
    var asInitVals = new Array();

    $(document).ready(function () {

        var oTable = $("#matches").dataTable({
            "iDisplayLength": 10,
            "bJQueryUI": true,
            "sDom": '<"top"i>t<"bottom"><p"clear">',
            "sPaginationType": "full_numbers",
            "aaSorting": []
        });


        $("thead input").keyup(function () {
            /* Filter on the column (the index) of this element */
            oTable.fnFilter(this.value, $("thead input").index(this));
        });

        /*
         * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
         * the footer
         */
        $("thead input").each(function (i) {
            asInitVals[i] = this.value;
        });

        $("thead input").focus(function () {
            if (this.className == "search_init") {
                this.className = "";
                this.value = "";
            }
        });

        $("thead input").blur(function () {
            if (this.value == "") {
                this.className = "search_init";
                this.value = asInitVals[$("thead input").index(this)];
            }
        });

        /* Apply the jEditable handlers to the table */

    });
</script>

@stop
