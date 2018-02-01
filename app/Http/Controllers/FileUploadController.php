<?php
/**
 * Created by PhpStorm.
 * User: phong
 * Date: 1/30/2018
 * Time: 4:50 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileUploadController extends Controller
{
    /**
     * Update the avatar for the user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request)
    {
        $path = $request->file('import')->store('import');

        return $path;
    }
}