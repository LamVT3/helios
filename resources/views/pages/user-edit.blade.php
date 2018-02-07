@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

            @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs]) @endcomponent

            @include('layouts.errors')

            <form id="edit-form" action="{{ route('users-save') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $user->id or '' }}"/>
                <!-- widget grid -->
                <section id="widget-grid" class="">
                    <!-- START ROW -->

                    <div class="row">

                        <article class="col-sm-12">

                            @component('components.jarviswidget',
                                ['id' => 0, 'icon' => 'fa-pencil', 'title' => explode('|', $page_title)[0]])
                                <div class="widget-body">
                                    <div class="row">
                                        <div class="smart-form col-lg-12">
                                            <section class="col-lg-12">
                                                <label class="label">Username *</label>
                                                <label class="input">
                                                    <input type="text" name="username" required
                                                           value="{{ old('username', isset($user) ? $user->username : '') }}">
                                                </label>
                                            </section>
                                            <section class="col-lg-12">
                                                <label class="label">Email *</label>
                                                <label class="input">
                                                    <input type="text" name="email" required
                                                           value="{{ old('email', isset($user) ? $user->email : '') }}">
                                                </label>
                                            </section>
                                            <section class="col-lg-12">
                                                <label class="label">Password</label>
                                                <label class="input">
                                                    <input type="password" name="password"
                                                           value="">
                                                </label>
                                            </section>
                                            <section class="col-lg-12">
                                                <label class="label">Confirm Password</label>
                                                <label class="input">
                                                    <input type="password" name="password_confirmation"
                                                           value="">
                                                </label>
                                            </section>
                                            <section class="col-lg-12">
                                                <label class="label">Rank *</label>
                                                <label class="select">
                                                    <select name="rank">
                                                        @for($i = 1; $i <= 8; $i++ )
                                                            <option value="{{ $i }}"  {{ $user->rank == $i ? "selected" : "" }}>{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                    <i></i>
                                                </label>
                                            </section>
                                            <section class="col-lg-12">
                                                <label class="label">Active?</label>
                                                <div class="inline-group">
                                                    <label class="radio">
                                                        <input type="radio" name="is_active"
                                                               value="1" {{ old('is_active', isset($user->is_active) ? $user->is_active : 0) == 1 ? 'checked' : '' }}>
                                                        <i></i> Yes</label>
                                                    <label class="radio">
                                                        <input type="radio" name="is_active"
                                                               value="0" {{ old('is_active', isset($user->is_active) ? $user->is_active : 0) != 1 ? 'checked' : '' }}>
                                                        <i></i> No</label>
                                                </div>
                                            </section>
                                            {{--<section class="col-lg-12">
                                                <label class="label">Role</label>
                                                <label class="select">
                                                    <select name="role_id">
                                                        @foreach($roles as $item)
                                                            <option value="{{ $item->id }}" {{ isset($user) && $user->hasRole($item->name) ? 'selected' : '' }}>{{ $item->display_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <i></i>
                                                </label>
                                            </section>--}}
                                        </div>
                                    </div>

                                </div>
                            @endcomponent

                        </article>

                    </div>

                    <!-- END ROW -->

                </section>
                <!-- end widget grid -->
                @component('components.form-actions') Submit @endcomponent
            </form>
        </div>
        <!-- END MAIN CONTENT -->

    </div>
    <!-- END MAIN PANEL -->
@endsection

@section('script')

    <!-- PAGE RELATED PLUGIN(S) -->
    <script src="{{ asset('js/plugin/jquery-form/jquery-form.min.js') }}"></script>

    <script type="text/javascript">

        // DO NOT REMOVE : GLOBAL FUNCTIONS!

        $(document).ready(function () {
            var errorClass = 'invalid';
            var errorElement = 'em';

            //CKEDITOR.replace('page_content', {extraPlugins: 'autogrow'});

            $.validator.addMethod( "alphanumeric", function( value, element ) {
                return this.optional( element ) || /^\w+$/i.test( value );
            }, "Letters, numbers, and underscores only please" );

            $('#edit-form').validate({
                errorClass: errorClass,
                errorElement: errorElement,
                highlight: function (element) {
                    $(element).parent().removeClass('state-success').addClass("state-error");
                    $(element).removeClass('valid');
                },
                unhighlight: function (element) {
                    $(element).parent().removeClass("state-error").addClass('state-success');
                    $(element).addClass('valid');
                },

                // Rules for form validation
                rules: {
                    username: {
                        required: true,
                        alphanumeric: true
                    },
                    email: {
                        required: true,
                        email: true
                    }
                },

                // Do not change code below
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            });

        })

    </script>
@stop