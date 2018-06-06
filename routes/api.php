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
			                         'source'      => $ad->source_name,
			                         'team'        => $ad->team_name,
			                         'marketer'    => $ad->creator_name,
			                         'campaign'    => $ad->campaign_name,
			                         'subcampaign' => $ad->subcampaign_name,
			                         'medium'      => $ad->medium,
		                         ] );

	}
	return response()->json( [ 'result' => 'Not found' ] );
} );
