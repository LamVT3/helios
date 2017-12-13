@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

            @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs]) @endcomponent

            @include('layouts.errors')

            <form id="edit-form" action="{{ route('users-roles-save') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $role->id or '' }}"/>
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
                                                <label class="label">Tên role *</label>
                                                <label class="input">
                                                    <input type="text" name="name"
                                                           value="{{ $role->name or '' }}">
                                                </label>
                                            </section>
                                            <section class="col-lg-12">
                                                <label class="label">Tên hiển thị</label>
                                                <label class="input">
                                                    <input type="text" name="display_name"
                                                           value="{{ $role->display_name or '' }}">
                                                </label>
                                            </section>
                                            <section class="col-lg-12">
                                                <label class="label">Mô tả</label>
                                                <label class="input">
                                                    <input type="text" name="description"
                                                           value="{{ $role->description or '' }}">
                                                </label>
                                            </section>
                                        </div>
                                    </div>

                                </div>
                            @endcomponent

                            @component('components.jarviswidget',
                                        ['id' => 2, 'icon' => 'fa-info-circle', 'title' => 'Permissions'])
                                <div class="widget-body">
                                    <div class="row">
                                        <div class="smart-form col-lg-12">
                                            <section>
                                                <label class="label">
                                                    <a class="select-all" href="javascript:void(0)">Select All</a> | <a
                                                            class="clear-all" href="javascript:void(0)">Clear All</a>
                                                </label>
                                            </section>
                                            @foreach($perms as $perm)
                                                <section class="col-sm-6">
                                                    <input type="hidden" name="perms[]" value="1" />
                                                    <div class="inline-group">
                                                        <label class="checkbox">
                                                            <input class="perm" type="checkbox" name="perms[]"
                                                                   {{ isset($role) && $role->hasPermission($perm->name) ? 'checked' : '' }}
                                                                   value="{{ $perm->id }}">
                                                            <i></i>{{ $perm->display_name }}</label>
                                                    </div>
                                                </section>
                                            @endforeach
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
    <script src="{{ asset('js/plugin/ckeditor/ckeditor.js') }}"></script>

    <script type="text/javascript">

        // DO NOT REMOVE : GLOBAL FUNCTIONS!

        $(document).ready(function () {
            var errorClass = 'invalid';
            var errorElement = 'em';

            //CKEDITOR.replace('page_content', {extraPlugins: 'autogrow'});

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
                    name: {
                        required: true
                    }
                },

                // Messages for form validation
                messages: {
                    title: {
                        required: 'Nhập tên'
                    }
                },

                // Do not change code below
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            });

            $('.select-all').click(function(){
                $('.perm').prop("checked", true);
            });

            $('.clear-all').click(function () {
                $('.perm').prop("checked", false);
            });
        })

    </script>
@stop