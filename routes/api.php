<?php

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware( 'auth:api' )->get( '/user', function ( Request $request ) {
	return $request->user();
} );

Route::post( '/get-ads', function ( Request $request ) {
	$ads_name = $request->get('name');
	if (!$ads_name)
		return response()->json( [ 'result' => 'Ad name doesn\'t exists' ] );
	$ad = \App\Ad::where( 'name', $ads_name )->first();
	if ( $ad ) {
		return response()->json( [
			                         'utm_source'      => $ad->source_name,
			                         'utm_team'        => $ad->team_name,
			                         'utm_agent'       => $ad->creator_name,
			                         'utm_campaign'    => $ad->campaign_name,
			                         'utm_subcampaign' => $ad->subcampaign_name,
			                         'utm_medium'      => $ad->medium,
		                         ] );

	}
	return response()->json( [ 'result' => 'Not found' ] );
} );

Route::post( '/get-thankyou-page', function ( Request $request ) {
	$ads_name = $request->get('ad_name');
	if (!$ads_name)
		return response()->json( [ 'result' => 'Field ad_name doesn\'t exists' ] );
	$ad = \App\Ad::where( 'name', $ads_name )->first();
	if ( $ad ) {
		$channel = \App\Channel::findOrFail($ad->channel_id);
		if ($channel){
			return response()->json( [
				                         'url'      => $channel->thankyou_page_url,
			                         ] );
		}
	}
	return response()->json( [ 'result' => 'Not found' ] );
} );
