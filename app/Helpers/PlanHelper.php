<?php

  function planFeature ($keyPath, $default = null)
  {
  	$tenant = app('tenant');

  	if ($tenant && $tenant->id == 1) {
        return true;
    }

  	if (!$tenant) return $default;

  	$subscription 	= $tenant->currentSubscription;
  	$plan 			= $subscription->plan;
  	$features 		= json_decode($plan->features, true);

  	return data_get($features, $keyPath, $default);

  }