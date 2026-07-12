<?php

namespace App\Http\Controllers;

use App\machine;
use App\packaging_machine;
use App\pressing_machine;
use App\stitching_machine;
use App\washing_machine;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    //
    function packaging_machine_delete(Request $request)
    {
        $m=packaging_machine::find($request->id);
        if($m->delete())
        {
            return redirect()->route("packaging_machinelist")
                ->with("message","Machine Delete Successfully");
        }
    }

    function washing_machine_delete(Request $request)
    {
        $m=washing_machine::find($request->id);
        if($m->delete())
        {
            return redirect()->route("washing_machinelist")
                ->with("message","Machine Delete Successfully");
        }
    }

    function pressing_machine_delete(Request $request)
    {
        $m=pressing_machine::find($request->id);
        if($m->delete())
        {
            return redirect()->route("pressing_machinelist")
                ->with("message","Machine Delete Successfully");
        }
    }

    function stitching_machine_delete(Request $request)
    {
        $m=stitching_machine::find($request->id);
        if($m->delete())
        {
            return redirect()->route("stitching_machinelist")
                ->with("message","Machine Delete Successfully");
        }
    }
    function machine_delete(Request $request)
    {
        $m=machine::find($request->id);
        if($m->delete())
        {
            return redirect()->route("machinelist")
                ->with("message","Machine Delete Successfully");
        }
    }

    function packaging_machine_update(Request $request)
    {
        $validated = $request->validate([
            'machine_name' => 'required|max:255',
        ]);
        $m=packaging_machine::find($request->id);
        $m->machine_name=$request->machine_name;
        if($m->save())
        {
            return redirect()->route("packaging_machinelist")
                ->with("message","Machine Update Successfully");
        }
    }

    function washing_machine_update(Request $request)
    {
        $validated = $request->validate([
            'machine_name' => 'required|max:255',
        ]);
        $m=washing_machine::find($request->id);
        $m->machine_name=$request->machine_name;
        if($m->save())
        {
            return redirect()->route("washing_machinelist")
                ->with("message","Machine Update Successfully");
        }
    }

    function stitching_machine_update(Request $request)
    {
        $validated = $request->validate([
            'machine_name' => 'required|max:255',
        ]);
        $m=stitching_machine::find($request->id);
        $m->machine_name=$request->machine_name;
        if($m->save())
        {
            return redirect()->route("stitching_machinelist")
                ->with("message","Machine Update Successfully");
        }
    }

    function pressing_machine_update(Request $request)
    {
        $validated = $request->validate([
            'machine_name' => 'required|max:255',
        ]);
        $m=pressing_machine::find($request->id);
        $m->machine_name=$request->machine_name;
        if($m->save())
        {
            return redirect()->route("pressing_machinelist")
                ->with("message","Machine Update Successfully");
        }
    }

    function machine_update(Request $request)
    {
        $validated = $request->validate([
            'machine_name' => 'required|max:255',
        ]);
        $m=machine::find($request->id);
        $m->machine_name=$request->machine_name;
        if($m->save())
        {
            return redirect()->route("machinelist")
                ->with("message","Machine Update Successfully");
        }
    }

    function stitching_machine_edit(Request $request)
    {
        $data=stitching_machine::find($request->id);
        return view("admin.machine.stitchingMachineEdit",compact("data"));
    }

    function washing_machine_edit(Request $request)
    {
        $data=washing_machine::find($request->id);
        return view("admin.machine.washingMachineEdit",compact("data"));
    }

    function packaging_machine_edit(Request $request)
    {
        $data=packaging_machine::find($request->id);
        return view("admin.machine.packagingMachineEdit",compact("data"));
    }

    function pressing_machine_edit(Request $request)
    {
        $data=pressing_machine::find($request->id);
        return view("admin.machine.pressingMachineEdit",compact("data"));
    }

    function machine_edit(Request $request)
    {
        $data=machine::find($request->id);
        return view("admin.machine.edit",compact("data"));
    }

    function packaging_machine_save(Request $request)
    {
        $validated = $request->validate([
            'machine_name' => 'required|unique:pressing_machine|max:255',
        ]);
        $m=new packaging_machine();
        $m->machine_name=$request->machine_name;
        if($m->save())
        {
            return redirect()->route("packaging_machinelist")
                ->with("message","New Packaging Machine Create Successfully");
        }
    }

    function pressing_machine_save(Request $request)
    {
        $validated = $request->validate([
            'machine_name' => 'required|unique:pressing_machine|max:255',
        ]);
        $m=new pressing_machine();
        $m->machine_name=$request->machine_name;
        if($m->save())
        {
            return redirect()->route("pressing_machinelist")
                ->with("message","New Pressing Machine Create Successfully");
        }
    }

    function washing_machine_save(Request $request)
    {
        $validated = $request->validate([
            'machine_name' => 'required|unique:washing_machine|max:255',
        ]);
        $m=new washing_machine();
        $m->machine_name=$request->machine_name;
        if($m->save())
        {
            return redirect()->route("washing_machinelist")
                ->with("message","New Washing Machine Create Successfully");
        }
    }

    function stitching_machine_save(Request $request)
    {
        $validated = $request->validate([
            'machine_name' => 'required|unique:stitching_machine|max:255',
        ]);
        $m=new stitching_machine();
        $m->machine_name=$request->machine_name;
        if($m->save())
        {
            return redirect()->route("stitching_machinelist")
                ->with("message","New Stitching Machine Create Successfully");
        }
    }

    function machine_save(Request $request)
    {
        $validated = $request->validate([
            'machine_name' => 'required|unique:machine|max:255',
        ]);
        $m=new machine();
        $m->machine_name=$request->machine_name;
        if($m->save())
        {
            return redirect()->route("machinelist")
                ->with("message","New Machine Create Successfully");
        }
    }

    function machine_add(Request $request)
    {
        return view("admin.machine.create");
    }
    function machine_list(Request $request)
    {
        $data=machine::orderBy("id","desc")->paginate(10);

        return view("admin.machine.list")->with(['data'=>$data]);
    }

    function stitching_machine_list(Request $request)
    {
        $data=stitching_machine::orderBy("id","desc")->paginate(10);

        return view("admin.machine.stitchingMachineList")->with(['data'=>$data]);
    }

    function washing_machine_list(Request $request)
    {
        $data=washing_machine::orderBy("id","desc")->paginate(10);

        return view("admin.machine.washingMachineList")->with(['data'=>$data]);
    }

    function pressing_machine_list(Request $request)
    {
        $data=pressing_machine::orderBy("id","desc")->paginate(10);

        return view("admin.machine.pressingMachineList")->with(['data'=>$data]);
    }

    function packaging_machine_list(Request $request)
    {
        $data=packaging_machine::orderBy("id","desc")->paginate(10);

        return view("admin.machine.packagingMachineList")->with(['data'=>$data]);
    }
}
