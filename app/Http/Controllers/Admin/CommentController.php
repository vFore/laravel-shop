<?php

namespace App\Http\Controllers\Admin;

use App\Models\GoodsComment;
use App\Models\GoodsCommentReply;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Permission;

class CommentController extends Controller
{
    protected $perms;

    /**
     * AdminRoleController constructor.
     */
    public function __construct()
    {
        $this->perms = new Permission;
    }

    /**
     * @return  view    商品评论列表页
     */
    public function index(Request $request)
    {
        //判断是否有权限访问列表
		$this->perms->adminPerms('admin, comment', 'comment_list');

        //获取搜索提交过来的会员名
        $username = $request->input('username');

        //获取搜索提交过来的内容
        $content = $request->input('content');

//        $data = GoodsComment::all();
//
//        dd($data);

        //查询评论信息
            $data = GoodsComment::where('users_login.login_name','like','%'.$username.'%')
                ->where('goods_comment.comment_info','like','%'.$content.'%')
                ->join('users_login','goods_comment.user_id','=','users_login.user_id')
                ->join('goods','goods_comment.goods_id','=','goods.goods_id')
                ->select('goods_comment.*','users_login.login_name','goods.goods_name')
                ->get();

        //统计总数
            $sum = count($data);

        return view('admin.main.comment.index',compact('data','sum'));
    }

    /**
     * destroy  商品评论删除
     *
     * @param   $request    array   获取请求头信息
     *
     */
    public function destroy(Request $request)
    {
        //判断是否有权限删除
        $error = $this->perms->adminDelPerms('admin, comment', 'delete_comment');
        if ($error){
            $status = 0;
            return $status;
        }

        $id = trim($request->input('id'),',');


//        foreach (explode(',',$id) as $value){
//
//            $commentlist = GoodsComment::find($value)->reply->toArray();
//
//
//        }

        $arrid = explode(',',$id);

        $status = 0;

        foreach ($arrid as $value){

           $sta = GoodsComment::where('id',$value)->delete();

           $stat = GoodsCommentReply::where('comment_id',$value)->delete();

           if(!$sta){
               $status = 1;
           }

        }

        return $status;
    }

    public function show($id)
    {

//        $data = GoodsCommentReply::all();
//        dd($data);

       $data =  GoodsCommentReply::where('comment_id', '=', $id)
           ->leftJoin('admin_users','goods_comment_reply.admin_id','=','admin_users.id')
           ->leftJoin('users_login','goods_comment_reply.user_id','=','users_login.user_id')
           ->select('goods_comment_reply.*','users_login.login_name','admin_users.nickname')
           ->get();

       return view('admin.main.comment.show',compact('data','id'));

    }

    public function store(Request $request)
    {


    }


    public function edit($id)
    {
        //判断是否有权限修改
		$this->perms->adminPerms('admin, comment', 'edit_comment');

        $data = GoodsComment::findOrfail($id);

        $show = $data->is_show;

        switch ($show){
            case 0:
                $show = 1;
                break;
            case 1:
                $show = 0;
                break;
            default:
                return '{"error":"1"}';
                break;
        }

        $result = GoodsComment::where('id','=',$id)->update(['is_show'=>$show]);

        if($result == 0){
            return '{"error":"2"}';
        }

        return '{"error":"0"}';
    }

    public function update(Request $request,$id)
    {
        if (!$id){
            return 1;
        }

        if ($request->input('reply_info') == ""){
            return 2;
        }

        $reply = new GoodsCommentReply();

        $adminid = Auth::guard('admin')->user()->id;

        $reply->comment_id = $id;

        $reply->admin_id = $adminid;

        $reply->reply_info = $request->input('reply_info');

        if ($reply->save()){
            return 0;
        }else{
            return 1;
        }

    }
}
