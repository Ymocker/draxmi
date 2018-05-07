<?php
namespace App\Http\Controllers\Frontend;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Balance;
use App\Cards;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('frontend.cards.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $balance = Balance::where('uid', Auth::user()->id)->first();
        return view('frontend.cards.create', $balance); //not more than user->balance
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        if (isset($_POST['no'])) {
            return view('frontend.cards.list');
        }
        
        $balance = Balance::where('uid', Auth::user()->id)->first();
        $this->validate($request, [
            'amount' => 'required|integer|min:1|max:' . $balance->balance,
            'end' => 'required|date_format:' . trans('cards.date-format-php') . '|after:' . Carbon::today(),
            'holder' => 'required|string',
        ]);
        
        $card = new Cards;

        $card->uid = Auth::user()->id;
        $card->end = Carbon::createFromFormat(trans('cards.date-format-php'), $request->end)->endOfDay();
        $card->climit = $request->amount;
        $card->cbalance = $request->amount;
        $card->cholder = trim(htmlspecialchars($request->holder, ENT_NOQUOTES, 'UTF-8'));
        $card->vnumber = rand(100001, 999998); //other method of getting vnumber ?
        $card->status = TRUE; // not deleted card
                
        $card->save();
        /*
         * maybe do something with transactions
         */
        
        return redirect('payment/card/' . $card->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //check, is $id card existed, issued current user and not deleted and not expired
        $card = Cards::find($id);
        
        if ((!isset($card)) or ($card->uid != Auth::user()->id)) {
            $data['message'] = trans('cards.no_issure');
            return view('frontend.cards.nocard', $data);
        }
        $data['c_number'] = $this->makeCardNumber($id);
        if (!$card->status) {
            $data['message'] = trans('cards.card_no') . ' ' . $data['c_number'] . trans('cards.was_del') . $card->updated_at->format(trans('cards.date-format-php')) . '.';
            return view('frontend.cards.nocard', $data);
        }
        if (strtotime($card->end) < time()) {
            $data['message'] = trans('cards.card_no') . ' ' . $data['c_number'] . trans('cards.was_exp') . $card->end->format(trans('cards.date-format-php')) . '.';
            return view('frontend.cards.nocard', $data);
        }
                
        $data['id'] = $id;
        $data['v_number'] = $card->vnumber;
        $data['c_end'] = $card->end->format(trans('cards.date-format-php'));
        $data['c_holder'] = $card->cholder;
        return view('frontend.cards.onecard', $data);
    }

    public function edit($id)
    {
        //echo 'card edit';
    }

    public function update(Request $request, $id)
    {
        //echo 'card update';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $card = Cards::find($id);
        $card->status = FALSE;
              
        $card->save();

        return redirect('payment/card');
        //do something with transactions if necessary
    }
    
    public function cardlist() //make json for DataTable
    {
        $result = Cards::where('uid', Auth::user()->id)->get();

        $items = Array();
        
        foreach ($result as $row) {
            if (!$row['status']) {
                $row['status'] = trans('cards.deleted');
            } elseif (strtotime($row['end']) < time()) {
                $row['status'] = trans('cards.expired');
            } else {
                $row['status'] = '<a href="card/' . $row['id'] . '">' . trans('cards.active') . '</a>';    
            }
            $row['id'] = $this->makeCardNumber($row['id']);
            $row['start'] = date( trans('cards.date-format-php'), strtotime($row['created_at']));
            $row['end_date'] = date( trans('cards.date-format-php'), strtotime($row['end']));
            $row['cbalance'];
            
            $items[] = $row;
        }

        $responce = ['data'=>$items];
        echo json_encode($responce);
    }
    
    private function makeCardNumber($id) { // make card number from it to XXX XXX XXX XXX
        $len = 1000000000000;
        $cardNumber = substr((number_format(($id + $len), 0, '', ' ')), 2);
        return $cardNumber;
    }
}
