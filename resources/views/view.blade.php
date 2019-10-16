<div class="crons">
    @foreach($crons as $cron)
        <div class="cron @if($cron->did_fail) error @elseif($cron->start_time === NULL) notrun @elseif($cron->end_time === NULL) running @else success @endif">
            <div class="cron_name">{{$cron->label}}</div>
            <div class="cron_time">{{$cron->next_expected_start}}</div>
            <div class="did_fail">@if($cron->did_fail) Error!
                - {{$cron->error_message}} @elseif($cron->start_time === NULL) Not done first cycle
                yet @elseif($cron->end_time === Null) Running @else
                    Successful @endif</div>
        </div>
    @endforeach
</div>
<style>
    @import url('https://fonts.googleapis.com/css?family=Francois+One&display=swap');

    * {
        font-family: 'Francois One', sans-serif;
    }

    .crons {
        display: grid;
        grid-template-columns: repeat(2, 49%);
        column-gap: 2%;
        row-gap: 3%;
    }

    .cron {
        display: grid;
        grid-template-columns: repeat(3, 33%);
        margin-bottom: 5px;
        padding: 10px;
        text-align: center;
    }

    .cron.error {
        background: darkred;
        color: #fff;
    }

    .cron.running {
        background: #3D68D4;
        color: #fff;
    }

    .cron.notrun {
        background: darkgrey;
        color: #fff;
    }

    .cron.success {
        background: forestgreen;
        color: #fff;
    }
</style>
<script>
    function sleep(time) {
        return new Promise((resolve) => setTimeout(resolve, time));
    }
    sleep(30000).then(() => {
        location.reload();
    });
</script>
