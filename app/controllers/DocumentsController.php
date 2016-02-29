<?php

class DocumentsController extends \BaseController {

	/**
	 * Display a listing of branches
	 *
	 * @return Response
	 */
	public function index()
	{
		$documents = Document::all();

		return View::make('documents.index', compact('documents'));
	}

	/**
	 * Show the form for creating a new branch
	 *
	 * @return Response
	 */
	public function create()
	{
		$employees = Employee::all();

		return View::make('documents.create', compact('employees'));
	}

	/**
	 * Store a newly created branch in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Document::$rules, Document::$messsages);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$document= new Document;
        
        $document->employee_id = Input::get('employee');

		$document->document_name = Input::get('type');

		if ( Input::hasFile('path')) {

            $file = Input::file('path');
            $name = time().'-'.$file->getClientOriginalName();
            $file = $file->move('public/uploads/employees/documents/', $name);
            $input['file'] = '/public/uploads/employees/documents/'.$name;
            $document->document_path = $name;
        }

        $document->description = Input::get('desc');

		$document->save();

		Audit::logaudit('Documents', 'create', 'created: '.$document->type);


		return Redirect::route('documents.index')->withFlashMessage('Employee document successfully uploaded!');
	}

	/**
	 * Display the specified branch.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$document = Document::findOrFail($id);

		return View::make('documents.show', compact('document'));
	}

	/**
	 * Show the form for editing the specified branch.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$document = Document::find($id);

		return View::make('documents.edit', compact('document'));
	}

	/**
	 * Update the specified branch in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$document = Document::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Document::rolesUpdate(), Document::$messsages);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}
		
		$document->document_name = Input::get('type');

		if ( Input::hasFile('path')) {

            $file = Input::file('path');
            $name = time().'-'.$file->getClientOriginalName();
            $file = $file->move('public/uploads/employees/documents/', $name);
            $input['file'] = '/public/uploads/employees/documents/'.$name;
            $document->document_path = $name;
        }

        $document->description = Input::get('desc');

		$document->update();

		Audit::logaudit('Documents', 'update', 'updated: '.$document->document_name);

		return Redirect::route('documents.index')->withFlashMessage('Employee Document successfully updated!');
	}

	/**
	 * Remove the specified branch from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$document = Document::findOrFail($id);
		Document::destroy($id);

		Audit::logaudit('Documents', 'delete', 'deleted: '.$document->document_name);

		return Redirect::route('documents.index')->withDeleteMessage('Employee Document successfully deleted!');
	}

    public function getDownload($id){
        //PDF file is stored under project/public/download/info.pdf
        $document = Document::findOrFail($id);
        $file= public_path(). "/uploads/employees/documents/".$document->document_path;
        
        return Response::download($file, $document->document_name);
}

}
