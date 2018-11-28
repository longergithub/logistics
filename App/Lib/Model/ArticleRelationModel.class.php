<?php
	
	Class ArticleRelationModel extends RelationModel {
		
		//定义主表名称
		Protected $tableName = 'article';
		//定义关联关系
		Protected $_link = array(
			'attr' => array(
				'mapping_type' => MANY_TO_MANY,//多对多关系
				'mapping_name' => 'attr',
				'foreign_key' => 'bid',
				'relation_foreign_key' => 'aid',
				'relation_table' => 'wwt_article_attr',
			),
			'cate' => array(
				'mapping_type' => BELONGS_TO,//think提供的一对多反过来,多的一表成了主表
				'foreign_key' => 'cid',
				'mapping_fields' => 'name',//只需要显示的字段
				'as_fields' => 'name:cate',//提到外层数组里，：号后面cate是别名
			)
		);
		
		Public function getArticles ($type = 0) {
			$field = array('del');
			$where = array('del' => $type);
			return $this->field($field, true)->where($where)->relation(true)->select();//true关联所有，如果只想关联其中一个表，那就填上那个表名
		}
	}
?>