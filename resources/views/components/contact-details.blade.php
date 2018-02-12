<table id="table_contact_details" class="table table-striped table-bordered table-hover"
       width="100%">
    <tbody>
    <tr>
        <td>Name</td>
        <td>{{ $contact->name or '' }}</td>
    </tr>
    <tr>
        <td>Email</td>
        <td>{{ $contact->email or '' }}</td>
    </tr>
    <tr>
        <td>Phone</td>
        <td>{{ $contact->phone or '' }}</td>
    </tr>
    <tr>
        <td>Marketer</td>
        <td>{{ $contact->marketer or '-100' }}</td>
    </tr>
    <tr>
        <td>Campaign</td>
        <td>{{ $contact->campaign_name or '-100' }}</td>
    </tr>
    <tr>
        <td>Channel</td>
        <td>{{ $contact->subcampaign_name or '-100' }}</td>
    </tr>
    <tr>
        <td>Ads</td>
        <td>{{ $contact->ad_name or '-100' }}</td>
    </tr>
    <tr>
        <td>Landing page</td>
        <td>{{ $contact->landing_page or '' }}</td>
    </tr>
    <tr>
        <td>Link tracking</td>
        <td>{{ $contact->ads_link or '' }}</td>
    </tr>
    <tr>
        <td>Current Level</td>
        <td>{{ $contact->current_level or $contact->clevel }}</td>
    </tr>
    <tr>
        <td>Is Transferred?</td>
        <td>{{ $contact->is_transferred ? "Transferred" : "n/a" }}</td>
    </tr>
    <tr>
        <td>Is Valid</td>
        <td>{!!  $contact->clevel === "c3b" ? "<strong class='text-success'>Valid</strong>" : "<strong class='text-danger'>Invalid</strong>" !!}</td>
    </tr>
    <tr>
        <td>Invalid Reason</td>
        <td>{{ $contact->invalid_reason or '' }}</td>
    </tr>
    <tr>
        <td>Is Returned</td>
        <td>{{ $contact->is_returned ? "Returned" : "No" }}</td>
    </tr>
    <tr>
        <td>Returned Reason</td>
        <td>{{ $contact->returned_reason or '' }}</td>
    </tr>
    <tr>
        <td>Saleperson</td>
        <td>{{ $contact->sale_person or '' }}</td>
    </tr>
    <tr>
        <td>L1 Date</td>
        <td>{{ $contact->l1_time or '' }}</td>
    </tr>
    <tr>
        <td>L2 Date</td>
        <td>{{ $contact->l2_time or '' }}</td>
    </tr>
    <tr>
        <td>L3 Date</td>
        <td>{{ $contact->l3_time or '' }}</td>
    </tr>
    <tr>
        <td>L4 Date</td>
        <td>{{ $contact->l4_time or '' }}</td>
    </tr>
    <tr>
        <td>L5 Date</td>
        <td>{{ $contact->l5_time or '' }}</td>
    </tr>
    <tr>
        <td>L6 Date</td>
        <td>{{ $contact->l6_time or '' }}</td>
    </tr>
    <tr>
        <td>L7 Date</td>
        <td>{{ $contact->l7_time or '' }}</td>
    </tr>
    <tr>
        <td>L8 Date</td>
        <td>{{ $contact->l8_time or '' }}</td>
    </tr>
    <tr>
        <td>Browser</td>
        <td>{{ $contact->browser or '' }}</td>
    </tr>
    <tr>
        <td>Platform</td>
        <td>{{ $contact->platform or '' }}</td>
    </tr>
    </tbody>
</table>

<h3>Call history</h3>

<table id="table_call_history" class="table table-striped table-bordered table-hover"
       width="100%">
    <thead>
    <tr>
        <th>Time</th>
        <th>Old Level</th>
        <th>New Level</th>
        <th>Comment</th>
        <th>Status</th>
        <th>Audio</th>
    </tr>
    </thead>
    <tbody>
    @if($contact->call_history)
        @foreach ($contact->call_history as $item)
            <tr id="">
                <td>{{ $item["date"] }}</td>
                <td>{{ $item["level_old"] }}</td>
                <td>{{ $item["level_new"] }}</td>
                <td>{{ $item["comment"] }}</td>
                <td>{{ $item["call_status_new_desc"] }}</td>
                <td>
                    <audio controls>
                        <source src="{{ $item["link_record"] }}" type="audio/mpeg">
                        Your browser does not support the audio tag.
                    </audio>
                </td>
            </tr>
        @endforeach
    @endif

    </tbody>
</table>