<?php

namespace App\Http\Controllers;

use App\Models\FestivalReg;
use Illuminate\Http\Request;

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
            'email' => 'required|email|unique:festival_regs',
            'mobile' => 'required|unique:festival_regs',
            'ticket_number' => 'required|unique:festival_regs',
        ]);
        FestivalReg::create($request->all());

        return redirect()->route('festival_regs.index')
            ->with('success', 'Registration created successfully.');
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
