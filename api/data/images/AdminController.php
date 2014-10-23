<?php

class AdminController extends Controller
{
	
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/admin';
	//public $layout='//layouts/column2';
	

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('q'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','index','settings'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	public function actionSettings()
	{
		if (Yii::app()->request->isAjaxRequest){
			
			if(Yii::app()->cache->flush())echo 'Кэш очищен!';
		}else{
		
			$model=About::model()->findByPk(1);
			$user=User::model()->findByPk(1);
		
			if(isset($_POST['About']))
			{
				$model->attributes=$_POST['About'];
				if($model->save())
					$this->redirect(array('admin/settings'));
			}
			
			if(isset($_POST['Users']))
			{
				if(isset($_POST['Users']['pass'])){
					//$user->attributes=$_POST['Users'];
					$user->pass=$_POST['Users']['pass'];
					$user->pass_rep=$_POST['Users']['pass_rep'];
					//print_r($_POST['Users']);
					if($user->save(true, array('pass')))
						 $this->redirect(array('admin/settings'));}
				
				if(isset($_POST['Users']['email'])){
					$user->email=$_POST['Users']['email'];
					
					if($user->save(true, array('email')))
						$this->redirect(array('admin/settings'));}
			}
		

		$this->render('settings',array(
			'model'=>$model,
			'user'=>$user,
		));
		}
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Rubrics;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['rubrics']))
		{
			$model->attributes=$_POST['rubrics'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->rubrics_id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['rubrics']))
		{
			$model->attributes=$_POST['rubrics'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->rubrics_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}


	//+ 
	public function actionIndex()
	{
        $rubricsData = Rubrics::model()->findAll();
        $rubricsTree = Rubrics::model()->dbResultToForest($rubricsData,'rubrics_id','rubrics_parent','rubrics_name');
	
		$this->render('index',array(
			'rubricsTree'=>$rubricsTree,
		));
	
	}
	
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Rubrics('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['rubrics']))
			$model->attributes=$_GET['rubrics'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Rubrics the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Rubrics::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Rubrics $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='rubrics-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}