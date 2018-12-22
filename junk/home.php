<?php

    require_once "nest.class.php";

    function getWeather() {
        print "fetching current weather\n";
    	$x = file_get_contents("https://api.openweathermap.org/data/2.5/weather?id=5505411&APPID=". OPENWEATHERAPI_KEY ."&mode=json&units=imperial");
    	$weather	= json_decode($x);
        return $weather;
    }

    function getForecastAPI() {
        print "fetching current forecast\n";
    	$x = file_get_contents("https://api.openweathermap.org/data/2.5/forecast?id=5505411&APPID=". OPENWEATHERAPI_KEY ."&mode=json&units=imperial");
    	$weather	= json_decode($x);
        return $weather;
    }


    function getForecast() {
    	$forecast		= getForecastAPI();
    	$temps			= ['max' => -999, 'min' => 999];
    	$forecasts		= [];
    	$forecast15H	= [];
    	foreach ($forecast->list as $data) {
    		$diff = $data->dt - time();
    		if ($diff < 86400) {
    			$temps['max']	= max($temps['max'], $data->main->temp);
    			$temps['min']	= min($temps['min'], $data->main->temp);
    		}

    		if ($diff < (3600 * 18)) {
    			$forecast15H[]	= ['time' => date("g:i A", $data->dt), 'weather' => ucwords($data->weather[0]->description), 'temp' => $data->main->temp];
    			$temps['min']	= min($temps['min'], $data->main->temp);
    		}

    		$fdate	= date("Y-m-d", $data->dt);
    		$fdatef	= date("F d, Y", $data->dt);

    		$wtype	= $data->weather[0]->main;
    		$wdesc	= $data->weather[0]->description;
    		if (!isset($forecasts[$fdate]['main'][$wtype])) $forecasts[$fdate]['main'][$wtype] = 0;
    		if (!isset($forecasts[$fdate]['desc'][$wtype][$wdesc])) $forecasts[$fdate]['desc'][$wtype][$wdesc] = 0;
            $forecasts[$fdate]['date']  = $fdatef;
    		$forecasts[$fdate]['main'][$wtype]++;
    		$forecasts[$fdate]['desc'][$wtype][$wdesc]++;

    		if (!isset($forecasts[$fdate]['temp'])) $forecasts[$fdate]['temp'] = ['max' => -999, 'min' => 999];
    		$forecasts[$fdate]['temp']['max']	= max($forecasts[$fdate]['temp']['max'], $data->main->temp);
    		$forecasts[$fdate]['temp']['min']	= min($forecasts[$fdate]['temp']['min'], $data->main->temp);

    		//printf("%8d %4.1fF  %-15s %s", $diff, $data->main->temp, $data->weather[0]->main, $data->weather[0]->description);
    		//print "\n";
    	}

    	//printf("high: %4.1fF  low: %4.1fF\n", $temps['max'], $temps['min']);

    	//var_dump($forecasts);

    	$days	= 0;
    	$forecast3D	= [];
    	foreach ($forecasts as $day => $data) {
    		asort($data['main']);
    		$mainA	= array_flip($data['main']);
    		$main	= array_pop($mainA);

    		asort($data['desc'][$main]);
    		$descA	= array_flip($data['desc'][$main]);
    		$desc	= ucwords(array_pop($descA));
    		$forecast3D[$day]	= ['date' => $data['date'], 'weather' => $desc, 'temp' => $data['temp']];
    		$days++;
    		if ($days > 3) break;
    	}

        /*
    	foreach ($forecast15H as $f15) {
    		printf("%8s - %-20s  %4.1fF\n", $f15['time'], $f15['weather'], $f15['temp']);
    	}

    	foreach ($forecast3D as $f3D) {
    		printf("%10s - %-20s  High %4.1fF  Low %4.1fF\n", $f3D['date'], $f3D['weather'], $f3D['temp']['max'], $f3D['temp']['min']);
    	}
        */

        return ['15-hour' => $forecast15H, '3-day' => $forecast3D];
    }


	function getHome() {

        print "fetching nest status\n";

        $nest = new Nest(NEST_USER, NEST_PASS);
        $locations      = $nest->getUserLocations();
        $devices_serials        = $nest->getDevices();
        $nestinfo               = $nest->getDeviceInfo($devices_serials[0]);

        $statuses       = array();
        if ($nestinfo->current_state->auto_away) $statuses[]    = "auto-away (". $nestinfo->auto_away .")";
        if ($nestinfo->current_state->manual_away) $statuses[]  = "away";
        if ($nestinfo->current_state->fan) $statuses[]  = "fan on";
        if ($nestinfo->current_state->ac) $statuses[]   = "cooling";
        if ($nestinfo->current_state->heat) $statuses[] = "heating";
        if ($nestinfo->current_state->leaf) $statuses[] = "leaf";

        $netstatus      = ($nestinfo->network->online ? "online" : "offline") .", last update ". round((time() - strtotime($nestinfo->network->last_connection)) / 60) ."m ago";

        $status         = "";
        if (!empty($statuses)) {
                $status = implode(", ", $statuses);
        }

		return [
			'temperature'	=> $nestinfo->current_state->temperature,
			'humidity'		=> $nestinfo->current_state->humidity,
		];

        printf("Romhaus: ".
                "%.1f°%s, %d%% humidty (target %d°%s, %s) - ".
                "Outside: %.1f°%s, %d%% humidity - (%s - battery %.3fV)",
                $nestinfo->current_state->temperature,
                $nestinfo->scale,
                $nestinfo->current_state->humidity,
                $nestinfo->target->temperature,
                $nestinfo->scale,
                $status,
                $locations[0]->outside_temperature,
                $nestinfo->scale,
                $locations[0]->outside_humidity,
                $netstatus,
                $nestinfo->current_state->battery_level
                );

	}
