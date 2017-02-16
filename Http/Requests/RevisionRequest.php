<?php

namespace Litepie\Revision\Http\Requests;

use App\Http\Requests\Request as FormRequest;

class RevisionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->model = $this->route('revision');
        if (is_null($this->model)) {
            // Determine if the user is authorized to access revision module,
            return $this->formRequest->user($this->guard)->canDo('revision.revision.view');
        }

        if ($this->isWorkflow()) {
            // Determine if the user is authorized to change status of an entry,
            return $this->can($this->getStatus());
        }

        if ($this->isCreate() ||  $this->isStore()) {
            // Determine if the user is authorized to create an entry,
            return $this->can('create');
        }

        if ($this->isUpdate() ||  $this->isEdit()) {
            // Determine if the user is authorized to update an entry,
            return $this->can('update');
        }

        if ($this->isDelete()) {
            // Determine if the user is authorized to delete an entry,
            return $this->can('delete');
        }

        // Determine if the user is authorized to view the module.
        return $this->can('view');

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isStore()) {
            // validation rule for create request.
            return [
                'title' => 'required',
                'description' => 'required'
            ];
        }

        if ($this->isUpdate()) {
            // Validation rule for update request.
            return [
                'title' => 'required',
                'description' => 'required'
            ];
        }

        // Default validation rule.
        return [

        ];
    }
}