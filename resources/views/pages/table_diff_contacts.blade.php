<div class="col-md-6">
    <h1>Helios vs MOL</h1>
    <div class="row">
        <article class="col-sm-12 col-md-12">
            <table id="table_mol_helios" class="display table table-striped table-bordered table-hover" width="100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Age</th>
                    <th>Submit</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($helios_diff as $item)
                    <tr id="contacts-{{ $item->id }}">
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>{{ $item->age }}</td>
                        <td>{{ $item->submit_time ? date('d-m-Y H:i:s', $item->submit_time/1000) : ""}}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </article>
    </div>
</div>
<div class="col-md-6">
    <h1>MOL vs Helios</h1>
    <div class="row">
        <article class="col-sm-12 col-md-12">
            <table id="table_helios_mol" class="display table table-striped table-bordered table-hover" width="100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Age</th>
                    <th>Submit</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($mol_diff as $item)
                    <tr>
                        <td>{{ @$item['name'] }}</td>
                        <td>{{ @$item['email'] }}</td>
                        <td>{{ @$item['phone'] }}</td>
                        <td>{{ @$item['age'] != '20 - 30 ??' ? $item['age'] : 21 }}</td>
                        <td>{{ @$item['datetime_submitted'] ? date('d-m-Y h:m:s',
                        strtotime($item['datetime_submitted'])) : ''}}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </article>
    </div>
</div>