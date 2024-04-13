<template>
	<div class="c-form__item">
		<div class="c-form__labelArea">
			<label :for="name">{{ title }}</label>
			<span v-if="requiredFlg" class="c-table__badge--require">必須</span>
			<span v-else class="c-table__badge--any">任意</span>
		</div>
		<textarea
			:id="name" :value="value" :name="name" :rows="rows" 
			:maxlength="textLength" :placeholder="placeholder"
			:style="{backgroundColor: inputColor}" 
			@input="$emit('input', $event.target.value)" required="required"></textarea>
		<div class="c-form__validationText">
			<span :style="{color: inputLengthColor}">{{ inputLength }}</span> / {{ textLength }}
		</div>
		<div class="c-table__errMsg" v-if="errorText" v-html="errorText"></div>
	</div>
</template>

<script>
export default {
	props: [
		'title',
		'name',
		'rows',
		'textLength',
		'placeholder',
		'value',
		'errorText',
		'requiredFlg',
	],
	computed:{
		// バリデーション失敗時、フォームの背景色を薄い赤色に変更
		inputColor:function(){
			if(this.errorText){
				return '#fcc8c8';
			}
		},
		// 入力フォームの文字数を取得
		inputLength:function(){
			if(this.value){
				return this.value.length;
			}else{
				return 0;
			}
		},
		// 入力フォームで文字数が制限以上なら、入力した文字数を赤色に変更
		inputLengthColor: function(){
			if(this.inputLength >= this.textLength){
				return 'rgb(255,0,0)';
			}
		}
	}
}
</script>
