<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class MyController extends Controller
{
    public function viewuser(){
        if (session('pengguna')){
            if (session('level')==1){
                $listofuser = \App\user::all();
            }else{
                $listofuser = \App\user::where('nomatrik','=',session('pengguna'))
                            ->get();
            }           
            return view('viewUser',compact('listofuser'));      
        }else{
            echo "Please login first!";
        }
    }
	
	public function insertnewuser(Request $r){
			$rows = \App\user::where('nomatrik','=',$r->nomatrik)
					->orWhere ('email','=',$r->email)
					->count();
			if($rows != 0){
				echo "NoMatrik or Email already exists";
			}
			else{
				$newuser = new \App\user;
				$newuser->nama = $r->nama;
				$newuser->nomatrik = $r->nomatrik;
				$newuser->email = $r->email;
				$newuser->password = sha1($r->password);
				$newuser->save();		
				return redirect('user');
			}
	}
	public function searchnewuser(Request $r){
		echo "Search [$r->nama]";
		$listofuser = \App\user::where('nama','like', $r->nama.'%')->get();
		return view('ViewUser', compact('listofuser'));
		
	}
	public function deleteuser(Request $r){
		$result = \App\user::where('id', '=',$r->id)->delete();
		if($result){
			echo "Data deleted!";
			return redirect('user');
		}else{
			echo "Failed to delete!";
		}
		
	}
	public function edituser(Request $r){
		                                                                                             
		$data = \App\user::where('id','=', $r->id)->get();
		return view('user.userEdit', compact('data'));
		
	}
	public function editUserConfirmed(Request $r){
		                                                                                             
		$result = \App\user::where('id','=', $r->id)
				->update(['nama' => $r->nama,
				'email' => $r->email,
				'nomatrik' => $r->nomatrik
				]);
				
		if($result){
			return redirect('user');
		}else{
			
		}
	}
public function login(Request $r){
        $username=  $r->u;
        $password = $r->p;
        $password = sha1($password);
        $result = \App\user::where("nomatrik","=",$username)
                ->where("password","=",$password)
                ->get();
        if ($result->count()>0){
            echo "Berjaya";
			session(['pengguna' => $username,
			'email' => $result[0]->email,
			'nama' => $result[0]->nama,
			'level' => $result[0]->level
			]);
			return redirect('user');
        }else{
            echo "Not a valid login";

        }
}

public function logout(){
	session()->flush();
	return redirect('dologin');
}


	public function viewitem(){
        if (session('pengguna')){
            if (session('level')==1){
                $listofitem = \App\item::all();
            }else{
                $listofitem = \App\item::where('nomatrik','=',session('pengguna'))
                            ->get();
            }           
            return view('viewItem',compact('listofitem'));      
        }else{
            echo "Please login first!";
        }
    }
		public function insertnewitem(Request $r){
			$rows = \App\item::where('nama','=',$r->nama)
					->orWhere ('description','=',$r->description)
					->count();
			if($rows != 0){
				echo "NoMatrik or Email already exists";
			}
			else{
				$newitem = new \App\item;
				$newitem->nama = $r->nama;
				$newitem->description = $r->description;
				$newitem->save();		
				return redirect('item');
			}
	}
	public function searchnewitem(Request $r){
		echo "Search [$r->nama]";
		$listofitem = \App\item::where('nama','like', $r->nama.'%')->get();
		return view('ViewItem', compact('listofitem'));
		
	}
	public function deleteitem(Request $r){
		$result = \App\item::where('id', '=',$r->id)->delete();
		if($result){
			echo "Data deleted!";
			return redirect('item');
		}else{
			echo "Failed to delete!";
		}
		
	}
	
	public function edititem(Request $r){
		                                                                                             
		$data = \App\item::where('id','=', $r->id)->get();
		return view('item.itemEdit', compact('data'));
		
	}
	public function editItemConfirmed(Request $r){
		                                                                                             
		$result = \App\item::where('id','=', $r->id)
				->update(['nama' => $r->nama,
				'description' => $r->description
				]);
				
		if($result){
			return redirect('item');
		}else{
			echo "Failed to update!";
		}
	}
	
	
	public function viewAllItems(){
		if (session('pengguna')){
			$item = \App\item::all();
			return view('item/bookItem',compact('item'));		
		}else{
			echo "Please login first!";
		}
	}
	
	public function newbooking(Request $r){
		if (session('pengguna')){
			$item = \App\item::where("id","=",$r->id)
					->get();
			return view('item/newbooking',compact('item'));		
		}else{
			echo "Please login first!";
		}
	}
	
	public function newbooking_add(Request $r){
		if (session('pengguna')){
			$newuser = new \App\item_status;
			$newuser->id_item = $r->id_item;
			$newuser->date_book = $r->date;
			$newuser->username = session("pengguna");
			$newuser->description = $r->komen;
			$newuser->status = 0;
			$newuser->notes = "";
			$newuser->save();
		
			return redirect('page_bookitem');	
		}else{
			echo "Please login first!";
		}
	}
	
	public function statusbooking(Request $r){
		if (session('pengguna')){
			$item = \App\item_status::all();
					/*where("id","=",$r->id)
					->where("username","=",session("pengguna"))
					->get();*/
			return view('item/statusbooking',compact('item'));		
		}else{
			echo "Please login first!";
		}
	}
	
	
	
	
	
	
	
	public function viewAllItemsStatus(){
		if (session('pengguna')){
			$item = \App\item_status::all();
			return view('item/bookStatus',compact('item'));		
		}else{
			echo "Please login first!";
		}
	}
	
	/*public function searchnewitemstatus(Request $r){
		echo "Search [$r->nama]";
		$listofitem_status = \App\item_status::where('nama','like', $r->nama.'%')->get();
		return view('ViewItemStatus', compact('listofitem_status'));
		
	}*/
	public function deleteitemstatus(Request $r){
		$result = \App\item_status::where('id', '=',$r->id)->delete();
		if($result){
			echo "Data deleted!";
			return redirect('page_bookitem');
		}else{
			echo "Failed to delete!";
		}	
	}
	
	public function edititemstatus(Request $r){
		                                                                                             
		$data = \App\item_status::where('id','=', $r->id)->get();
		return view('item.itemstatusEdit', compact('data'));
		
	}
	public function editItemStatusConfirmed(Request $r){
		                                                                                             
		$result = \App\item_status::where('id','=', $r->id)
				->update(['date_book' => $r->date_book,
				'description' => $r->description
				]);
				
		if($result){
			return redirect('statusbooking');
		}else{
			echo "Failed to update!";
		}
	}
	
	public function approveitemstatus(Request $r){
		                                                                                             
		$result = \App\item_status::where('id', '=',$r->id)->approve();
		if($result){
			echo "Data deleted!";
			return redirect('page_bookStatus');
		}else{
			echo "Failed to delete!";
		}	
		
	}
	
	public function rejectitemstatus(Request $r){
		                                                                                             
		$result = \App\item_status::where('id', '=',$r->id)->reject();
		if($result){
			echo "Data deleted!";
			return redirect('page_bookStatus');
		}else{
			echo "Failed to delete!";
		}	
		
	}
}





/*
public function index()
    {
        return "Index of MyController";
    }
	
	public function CCC()
    {
        return "This is CCC function";
    }
*/	


//LIKE SQL
		//::where('email','LIKE','%gmail.com')
		//->get();
		
		//AND SQL
		//::where('id',"=",'1','and')
		//->where('nama',"=",'Suhailan')
		//->get();