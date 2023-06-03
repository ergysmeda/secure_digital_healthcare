<?php

namespace App\Repositories\Models;

use App\Models\Appointment;
use App\Repositories\BaseRepository;
use App\Traits\Listable;
use App\Traits\Searchable;

class AppointmentRepository extends BaseRepository
{
    use Listable;
    public function __construct()
    {
        parent::__construct(new Appointment());
    }

    public function getThisDoctorAppointments($id)
    {
        return $this->model->whereHas('healthcareProfessional', function ($query) use ($id){
            $query->where('healthcare_professional_id', '=', $id);
        })->with('status:id,status_name')->get()->toArray();
    }
    public function getThisPatientAppointments($id)
    {
        return $this->model->whereHas('patient', function ($query) use ($id){
            $query->where('patient_id', '=', $id);
        })->with('status:id,status_name')->get()->toArray();
    }
}




