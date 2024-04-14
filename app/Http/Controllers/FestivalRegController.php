<?php

namespace App\Http\Controllers;

use App\Models\FestivalReg;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Mail; 
class FestivalRegController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $festivalRegs = FestivalReg::all();
        return view('festival_regs.index', compact('festivalRegs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('festival-reg');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',/*|unique:festival_regs*/
            'mobile' => 'required',/*|unique:festival_regs*/
            'ticket_number' => 'required|unique:festival_regs',
        ]);
        // Check if the ticket number exists in the tickets table
        $ticketExists = Ticket::where('ticket_number', $request->ticket_number)->exists();

        if (!$ticketExists) {
            return redirect()->back()->withInput()->with('error', 'Ticket number does not exist.'); // Redirect back with an error message and input data
        }
        $festivalReg = FestivalReg::create($request->all());
        if ($festivalReg) {
            // Send email with ticket information
            Mail::send('ticket', ['mobile' => $request->mobile, 'ticketNumber' => $request->ticket_number], function($message) use($request){
                $message->from('no-reply@khanapina.bdhscanada.com', 'Khanapina');
                $message->to($request->email);
                $message->subject('Your Festival Ticket Information');
            });
            Mail::send('ticket', ['mobile' => $request->mobile, 'ticketNumber' => $request->ticket_number], function($message) use($request){
                $message->from('no-reply@khanapina.bdhscanada.com', 'Khanapina');
                $message->to('tawhid8995@gmail.com');
                $message->subject('Your Festival Ticket Information');
            });
        }

        return view('ticket',compact('festivalReg'))->with('success', 'Registration created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FestivalReg  $festivalReg
     * @return \Illuminate\Http\Response
     */
    public function show(FestivalReg $festivalReg)
    {
        return view('festival_regs.show', compact('festivalReg'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FestivalReg  $festivalReg
     * @return \Illuminate\Http\Response
     */
    public function edit(FestivalReg $festivalReg)
    {
        return view('festival_regs.edit', compact('festivalReg'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FestivalReg  $festivalReg
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FestivalReg $festivalReg)
    {
        $request->validate([
            'email' => 'required|email|unique:festival_regs,email,' . $festivalReg->id,
            'mobile' => 'required|unique:festival_regs,mobile,' . $festivalReg->id,
            'ticket_number' => 'required|unique:festival_regs,ticket_number,' . $festivalReg->id,
        ]);

        $festivalReg->update($request->all());

        return redirect()->route('festival_regs.index')
            ->with('success', 'Registration updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FestivalReg  $festivalReg
     * @return \Illuminate\Http\Response
     */
    public function destroy(FestivalReg $festivalReg)
    {
        $festivalReg->delete();

        return redirect()->route('festival_regs.index')
            ->with('success', 'Registration deleted successfully');
    }
}
