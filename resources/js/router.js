import Router from 'vue-router';
import Toppage from './views/Toppage.vue';
import Register from './views/Register.vue';
import Login from './views/Login.vue';
import PasswordRemindSend from './views/PasswordRemindSend.vue';
import PasswordRemindReceive from './views/PasswordRemindReceive.vue';
import Rule from './views/Rule.vue';
import Privacy from './views/Privacy.vue';
import Contact from './views/Contact.vue';
import Error from './views/Error.vue';
import Unauthorized from './views/Unauthorized.vue';
import LoginExpired from './views/LoginExpired.vue';
import Forbidden from './views/Forbidden.vue';
import NotFound from './views/NotFound.vue';
import Setting from './views/Setting.vue';
import ChangeEmail from './views/ChangeEmail.vue';
import UpdateEmail from './views/UpdateEmail.vue';
import ChangePassword from './views/ChangePassword.vue';
import Withdrawal from './views/Withdrawal.vue';
import WebResume from './views/WebResume.vue';
import EditProfile from './views/EditProfile.vue';
import EditJobCareer from './views/EditJobCareer.vue';
import EditSkill from './views/EditSkill.vue';
import EditSelfPromotion from './views/EditSelfPromotion.vue';
import Search from './views/Search.vue';
import JobShow from './views/JobShow.vue';
import BookmarkJobs from './views/BookmarkJobs.vue';
import Bord from './views/Bord.vue';
import Messages from './views/Messages.vue';
import Logout from './views/Logout.vue';
// vuexnのデータ使う
import store from './store/index';

const router = new Router({
    mode: 'history',
    routes: [
		// ランディングページ
		{
			path: '/job20/',
			name: 'toppage',
			component: Toppage,
			meta: { requiresAuth: false }
		},

		// 会員登録画面
		{
			path: '/job20/register',
			name: 'register',
			component: Register,
			meta: { requiresAuth: false },
		},
		// ログイン画面
		{
			path: '/job20/login',
			name: 'login',
			component: Login,
			props: true,
			meta: { requiresAuth: false }
		},

		// パスワード再発行メール送信画面
		{
			path: '/job20/password_remind_send',
			name: 'password_remind_send',
			component: PasswordRemindSend,
			meta: { requiresAuth: false }
		},
		// パスワード再発行認証画面
		{
			path: '/job20/password_remind_receive',
			name: 'password_remind_receive',
			component: PasswordRemindReceive,
			meta: { requiresAuth: false }
		},
		
		// 利用規約画面
		{
			path: '/job20/rule',
			name: 'rule',
			component: Rule,
			meta: { requiresAuth: false }
		},
		// プライバシーポリシー画面
		{
			path: '/job20/privacy',
			name: 'privacy',
			component: Privacy,
			meta: { requiresAuth: false }
		},
		// お問い合わせ画面
		{
			path: '/job20/contact',
			name: 'contact',
			component: Contact,
			meta: { requiresAuth: false }
		},
		
		// エラー画面
		{
			path: '/job20/error',
			name: 'error',
			component: Error,
			meta: { requiresAuth: false }
		},
		// ページの閲覧にはログインが必要な画面
		{
			path: '/job20/unauthorized',
			name: 'unauthorized',
			component: Unauthorized,
			meta: { requiresAuth: false }
		},
		// ログイン有効期限切れの画面
		{
			path: '/job20/login_expired',
			name: 'loginExpired',
			component: LoginExpired,
			meta: { requiresAuth: false }
		},
		// 閲覧権限なしの画面
		{
			path: '/job20/forbidden',
			name: 'forbidden',
			component: Forbidden,
			meta: { requiresAuth: false }
		},
		// 他のルーティングに該当しなかったものを全てNotFoundPage送り
		{
			path: '*',
			name: 'notFound',
			component: NotFound,
			meta: { requiresAuth: false }
		},

		// 各種設定画面
		{
			path: '/job20/setting',
			name: 'setting',
			component: Setting,
			meta: { requiresAuth: true }
		},
		// メールアドレス変更画面
		{
			path: '/job20/change_email',
			name: 'change_email',
			component: ChangeEmail,
			meta: { requiresAuth: true }
		},
		// メールアドレス変更処理
		{
			path: '/job20/update_email',
			name: 'update_email',
			component: UpdateEmail,
			meta: { requiresAuth: false }
		},
		// パスワード変更画面
		{
			path: '/job20/change_password',
			name: 'change_password',
			component: ChangePassword,
			meta: { requiresAuth: true }
		},
		// 退会画面
		{
			path: '/job20/withdrawal',
			name: 'withdrawal',
			component: Withdrawal,
			meta: { requiresAuth: true }
		},		
		
		// WEB履歴書画面
		{
			path: '/job20/web_resume',
			name: 'web_resume',
			component: WebResume,
			props: true,
			meta: { requiresAuth: true }
		},
		// プロフィール編集画面
		{
			path: '/job20/edit_profile',
			name: 'edit_profile',
			component: EditProfile,
			props: true,
			meta: { requiresAuth: true }
		},
		// 職務経歴編集画面
		{
			path: '/job20/edit_job_career/:id(\\d+)?', // idは数値以外の入力不可(省略可)
			name: 'edit_job_career',
			component: EditJobCareer,
			props: true,
			meta: { requiresAuth: true }
		},
		// 資格・スキル編集画面
		{
			path: '/job20/edit_skill',
			name: 'edit_skill',
			component: EditSkill,
			props: true,
			meta: { requiresAuth: true }
		},
		// 自己PR編集画面
		{
			path: '/job20/edit_self_promotion',
			name: 'edit_self_promotion',
			component: EditSelfPromotion,
			props: true,
			meta: { requiresAuth: true }
		},
		
		// 求人検索画面
		{
			path: '/job20/search*',
			name: 'search',
			component: Search,
			meta: { requiresAuth: true }
		},		
		// 求人詳細画面
		{
			path: '/job20/job/:id(\\d+)', // idは数値以外の入力不可
			name: 'job_show',
			component: JobShow,
			meta: { requiresAuth: true }
		},

		// 気になる求人一覧画面
		{
			path: '/job20/bookmark_jobs',
			name: 'bookmark_jobs',
			component: BookmarkJobs,
			meta: { requiresAuth: true }
		},

		// メッセージ詳細画面
		{
			path: '/job20/bord/:id(\\d+)', // idは数値以外の入力不可
			name: 'bord',
			component: Bord,
			props: true,
			meta: { requiresAuth: true }
		},

		// メッセージ一覧画面
		{
			path: '/job20/messages',
			name: 'messages',
			component: Messages,
			meta: { requiresAuth: true }
		},
		
		// ログアウト処理
		{
			path: '/job20/logout',
			name: 'logout',
			component: Logout,
			props: true,
			// meta: { requiresAuth: false }
		},
	] ,

	// ページ遷移したらトップまでスクロール
	// https://codekneading.com/blog/2019/11/vue-router-scroll-position.html#%E3%83%96%E3%83%A9%E3%82%A6%E3%82%B6%E3%83%90%E3%83%83%E3%82%AF%E6%99%82%E3%81%AB%E5%85%88%E9%A0%AD%E3%81%AB%E6%88%BB%E3%81%A3%E3%81%A1%E3%82%83%E3%81%86%E3%82%93%E3%81%A0%E3%81%91%E3%81%A9%EF%BC%9F
	scrollBehavior (to, from, savedPosition) {
		// 検索ページの場合
		if (to.name === 'search' && from.name === 'search') {
			// 検索ボタンをクリックした場合
			if(store.state.Pagination.isClickBtn){
				store.commit('Pagination/setIsClickBtn', false);
				return { x: 0, y: 0 };
			// ページネーションか並び替えボタンをクリックした場合
			}else{
				return;
			}

		} else if (savedPosition) {
			return new Promise((resolve, reject) => {
				setTimeout(() => {
					resolve(savedPosition);
				}, 500);
				// 設定時間後にスクロール開始
			});

		} else {
		   return { x: 0, y: 0 };
		}
	}
});

router.beforeEach((to, from, next) => {
	// リファラーを取得
	router['referrer'] = from;
	const isLogin = localStorage.getItem('isLogin');
	const loginLimit = localStorage.getItem('loginLimit');

	// ログインユーザーの場合
	if(isLogin === 'true'){
		// ログイン有効期限切れの場合
		if(loginLimit !== null && Date.now() > loginLimit && to.name !== 'logout') {
			// console.log('ログイン有効期限切れ');
			// ログアウト処理
			router.push({ name: 'logout', params: { loginExpired: true }});
			
		// ログイン済みユーザーがトップ・会員登録・ログインページにアクセスしたら、検索ページに遷移
		}else if(to.name === 'toppage' || to.name === 'register' || to.name === 'login') {
			// console.log('ログイン済みユーザーがトップ・会員登録・ログインページにアクセスしたら、検索ページに遷移');
			next('/job20/search')

		// ログイン済みユーザーがログイン有効期限切れページにアクセスしたら、検索ページに遷移
		}else if(to.name === 'loginExpired') {
			// console.log('ログイン済みユーザーがログイン有効期限切れページにアクセスしたら、検索ページに遷移');
			next('/job20/search')

		// ログイン済みユーザーがページの閲覧にはログインが必要なページにアクセスしたら、検索ページに遷移
		}else if(to.name === 'unauthorized') {
			// console.log('ログイン済みユーザーがページの閲覧にはログインが必要なページにアクセスしたら、検索ページに遷移');
			next('/job20/search')

		// その他はそのまま遷移
		}else{
			next();
		}	

	// 未ログインユーザーの場合
	}else {
		// 未ログインユーザーがログイン後しか閲覧できないページにアクセスしたら、ページの閲覧にはログインが必要な画面に遷移
		if(to.meta.requiresAuth) {
			// console.log('未ログインユーザーがログイン後しか閲覧できないページにアクセスしたら、ページの閲覧にはログインが必要な画面に遷移');
			next('/job20/unauthorized')

		// その他はそのまま遷移
		}else {
			next();
		}	
	}
})

export default router