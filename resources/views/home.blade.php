@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if(isset($result))
                            <div class="alert alert-success" role="alert">
                                {!! $result !!}
                            </div>
                        @endif
                        <form method="POST">
                            <label>Cron Label</label>
                            <input type="text" name="label" placeholder="Cron Label">

                            <label for="learning_mode">Learning Mode (If you aren't sure about your expected
                                duration)</label>
                            <input type="checkbox" id="learning_mode" name="learning" value="1"/>

                            <label>Expected Duration (In Minutes)</label>
                            <input type="text" name="expected_duration" placeholder="Expected Duration" value="15"
                                   id="expected_duration"/>
                            <label>Allowance over duration (In Minutes)</label>
                            <input type="text" name="allowance" placeholder="Allowance" value="3"/>

                            <label>Cron Pattern</label>
                            <input type="text" name="cron_pattern" placeholder="Cron Pattern" value="* * * * *"/>
                            @csrf
                            <input type="submit">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
