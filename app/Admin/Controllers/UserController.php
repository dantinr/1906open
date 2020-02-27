<?php

namespace App\Admin\Controllers;

use App\Model\UserModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\UserModel';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserModel());

        $grid->column('id', __('Id'));
        $grid->column('user_name', __('用户名'));
        $grid->column('email', __('邮箱'));
        //$grid->column('pass', __('Pass'));
        $grid->column('mobile', __('手机号'));
        $grid->column('created_at', __('Created at'));
        //$grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(UserModel::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_name', __('User name'));
        $show->field('email', __('Email'));
        $show->field('pass', __('Pass'));
        $show->field('mobile', __('Mobile'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new UserModel());

        $form->text('user_name', __('User name'));
        $form->email('email', __('Email'));
        $form->text('pass', __('Pass'));
        $form->mobile('mobile', __('Mobile'));

        return $form;
    }
}
