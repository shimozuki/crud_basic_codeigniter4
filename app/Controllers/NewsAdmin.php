<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use \App\Models\NewsModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class NewsAdmin extends BaseController
{
    public function index()
    {
        $news = new NewsModel();
        $data['newses'] = $news->findAll();
        echo view('admin_list_news', $data);
    }
    public function preview($id)
    {
        $news = new NewsModel();
        $data['news'] = $news->where('id', $id)->first();

        if (!$data['news']) {
            throw PageNotFoundException::forPageNotFound();
        }
        echo view('news_detail', $data);
    }
    public function create()
    {
        $validation =  \Config\Services::validation();
        $validation->setRules(['title' => 'required']);
        $isDataValid = $validation->withRequest($this->request)->run();

        if ($isDataValid) {
            $news = new NewsModel();
            $news->insert([
                "title" => $this->request->getPost('title'),
                "content" => $this->request->getPost('content'),
                "status" => $this->request->getPost('status'),
                "slug" => url_title($this->request->getPost('title'), '-', true)
            ]);
            return redirect('admin/news');
        }
		echo view('admin_create_news');
    }
	public function edit($id)
	{
		$news = new NewsModel();
        $data['news'] = $news->where('id', $id)->first();

		$validation =  \Config\Services::validation();
        $validation->setRules([
            'id' => 'required',
            'title' => 'required'
        ]);
		$isDataValid = $validation->withRequest($this->request)->run();

		if($isDataValid){
            $news->update($id, [
                "title" => $this->request->getPost('title'),
                "content" => $this->request->getPost('content'),
                "status" => $this->request->getPost('status')
            ]);
            return redirect('admin/news');
        }
		echo view('admin_edit_news', $data);
	}
	public function delete($id)
	{
		$news = new NewsModel();
        $news->delete($id);
        return redirect('admin/news');
	}
}