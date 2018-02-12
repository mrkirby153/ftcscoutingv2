<?php
function get_response_data($response, $question) {
    foreach ($response->data as $data) {
        if ($data->question_id == $question) {
            if(is_array($data->response_data)){
                return implode(", ", $data->response_data);
            } else {
                return $data->response_data;
            }
        }
    }
    return "";
}

?>

<html>
<table>
    <thead>
    <tr>
        <th>Team Number</th>
        <th>Match Number</th>
        @foreach($questions as $question)
            <th>{{$question->question_name}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($responses as $response)
        <tr>
            <td>{{$response->team_number}}</td>
            <td>{{$response->match_number}}</td>
            @foreach($questions as $question)
                <td>{{get_response_data($response, $question->id)}}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
</html>