<template>
	<div class="c-form__item">
		<div class="c-form__labelArea">
			<label :for="name">{{ title }}</label>
			<span v-if="requiredFlg" class="c-table__badge--require">必須</span>
			<span v-else class="c-table__badge--any">任意</span>
		</div>
		<input 
			:id="name" :value="value" :name="name" :type="type" 
			:maxlength="textLength" :placeholder="placeholder"
			:style="{backgroundColor: inputColor}" 
			@input="$emit('input', $event.target.value)" :required="requiredFlg" autocomplete="on">
		<div class="c-form__validationText">
			<span :style="{color: inputLengthColor}">{{ inputLength }}</span> / {{ textLength }}
		</div>
		<div class="c-table__errMsg" v-if="errorText">{{ errorText }}</div>
		<div v-if="message" class="c-form__validation">
			{{ message }}
		</div>
	</div>
</template>

<script>
export default {
	props: [
		'title',
		'name',
		'type',
		'textLength',
		'placeholder',
		'value',
		'errorText',
		'requiredFlg',
		'message',
	],
	computed:{
		// バリデーション失敗時、フォームの背景色を薄い赤色に変更
		inputColor:function(){
			if(this.errorText){
				return 'rgb(255, 190, 190)';
			}
		},
		// 入力フォームの文字数を取得
		inputLength:function(){
			if(this.value || this.value == 0){
				return String(this.value).length;
			}else if(this.value === null){
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
