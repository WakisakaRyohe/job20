<template>
	<div class="c-form__item">
		<div class="c-form__labelArea">
			<label :for="name">{{ title }}</label>
			<span v-if="requiredFlg" class="c-table__badge--require">必須</span>
			<span v-else class="c-table__badge--any">任意</span>
		</div>
		<select
			:id="name" :value="value" :name="name"
			:style="{backgroundColor: inputColor}" 
			@input="$emit('input', $event.target.value)">
			<option value="0" :selected="userPrefectureID != 0">選択してください</option>
			<option v-for="prefecture in prefectures" :key="prefecture.id" :value="prefecture.id" :selected="prefecture.id == userPrefectureID">{{ prefecture.prefecture_name }}</option>
		</select>
		<div class="c-table__errMsg" v-if="errorText" v-html="errorText"></div>
	</div>
</template>

<script>
export default {
	props: [
		'title',
		'name',
		'value',
		'user',
		'prefectures',
		'requiredFlg',
		'errorText',
	],
	computed:{
		// バリデーション失敗時、フォームの背景色を薄い赤色に変更
		inputColor:function(){
			if(this.errorText){
				return '#fcc8c8';
			}
		},
		userPrefectureID(){
			if(this.user){
				return this.user.prefecture_id;
			}
		}
	}
}
</script>
