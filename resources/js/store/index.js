import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';
import ApplyJob from './apply_job';
import Bord from './bord';
import Certification from './certification';
import ChangeEmail from './change_email';
import Commitment from './commitment';
import Contact from './contact';
import EditJobCareer from './edit_job_career';
import EditProfile from './edit_profile';
import EditSelfPromotion from './edit_self_promotion';
import EditSkill from './edit_skill';
import FormChanged from './form_changed';
import GoTop from './go_top';
import Header from './header';
import Login from './login';
import Modal from './modal';
import Pagination from './pagination';
import PasswordRemindSend from './password_remind_send';
import Register from './register';
import WebResume from './web_resume';
import Withdrawal from './withdrawal';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
	ApplyJob,
	Bord,
	Certification,
	ChangeEmail,
	Commitment,
	Contact,
	EditJobCareer,
	EditProfile,
	EditSelfPromotion,
	EditSkill,
	FormChanged,
	GoTop,
	Header,
	Login,
	Modal,
    Pagination,
	PasswordRemindSend,
	Register,
	WebResume,
	Withdrawal,
  },
  plugins: [createPersistedState(
		{
		  // ストレージのキーを指定。デフォルトではvuex
		  key: 'vuex',
		  // 管理対象のステートを指定
		  paths: [
			// 連絡掲示板
			'Bord.newMessagesArray',
			// メールアドレス変更
			'ChangeEmail.form.email_old',
			'ChangeEmail.form.email_new',
			'ChangeEmail.form.email_confirmation',
			// お問い合せ
			'Contact.form.name',
			'Contact.form.email',
			'Contact.form.type',
			'Contact.form.subject',
			'Contact.form.message',
			'Contact.message_height',
			'Contact.isSubmit',
			// 職務経歴書
			'EditJobCareer.job_career',
			'EditJobCareer.textarea_height',
			// プロフィール
			'EditProfile.profile',
			// 自己PR
			'EditSelfPromotion.self_promotion',
			'EditSelfPromotion.self_promotion_height',
			'EditSelfPromotion.motivation_height',
			// 資格・スキル
			'EditSkill.skill',
			'EditSkill.isSelectOthersLanguage',
			'EditSkill.categories',
			'EditSkill.categoryNum',
			'EditSkill.suggestList',
			'EditSkill.activeCheckArray',
			'EditSkill.isActiveTab',
			'EditSkill.isShowTable',
			'EditSkill.freeword',
			'EditSkill.isNoIndex',
			'EditSkill.inputFreeword',
			// トップに戻るボタン
			'GoTop.bottom',
			// ヘッダー
			'Header.activeMenuNum',
			// ログイン
			'Login.email',
			// ページネーション
			'Pagination.industry',
			'Pagination.occupation',
			'Pagination.employment_status',
			'Pagination.prefecture',
			'Pagination.annual_income',
			'Pagination.keyword',
			'Pagination.sort',
			'Pagination.commitments',
			'Pagination.categories',
			'Pagination.categoryNum',
			'Pagination.activeCheckArray',
			'Pagination.isShowTable',
			'Pagination.current_page',
			'Pagination.isActiveSearchPanel',
			'Pagination.isShowSidebar',
			'Pagination.isWidthS',
			'Pagination.isWidthM',
			'Pagination.isShowCarousel',
			// パスワードリマインダー
			'PasswordRemindSend.email',
			'PasswordRemindSend.errors.email',
			// 会員登録
			'Register.email',
			'Register.errors.email',
			// WEB履歴書
			'WebResume.isActive',
			'WebResume.isReverse',
			// 退会
			'Withdrawal.form.request',
			'Withdrawal.form.reason',
			'Withdrawal.form.withdrawal_message',
			'Withdrawal.message_height',
			'Withdrawal.isActiveCheck',
		  ],
		  // ストレージの種類を指定。デフォルトではローカルストレージ
		  storage: window.localStrage,
		}
	)]
});
