{{-- <!-- Display item type and purpose -->
<p>Item Type: {{ $request->item_type ?? 'N/A' }}</p>
<p>Purpose: {{ $request->purpose }}</p> --}}

<!-- Loop through borrowed items -->
<table cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>Item ID</th>
            <th>Equipment Serial</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($requestDetails as $item)
            <tr>
                <td>{{ $item->equipment_item }}</td>
                <td>{{ $item->equipment_serial }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
