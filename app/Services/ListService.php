<?php

namespace App\Services;


class ListService
{
    protected $repository;
    public array $queryHelper = [
        'all' => [
            'columns_required' => [
                'users.id',
                'users.name',
                'users.email',
                'users.role_id',
                'users.profile_picture',
            ],
            'with_columns' => [
                'role:id,role_name',
            ],
        ],
        'profile' => [
            'columns_required' => [
                'users.id',
                'users.name',
                'users.email',
                'users.role_id',
            ],
            'with_columns' => [
                'userProfile:id,user_id,name,contact_details',
                'providerProfile:id,user_id,qualification,specialty,years_of_experience',
                'providerProfile.qualifications:id,provider_profile_id,qualification_name,institution_name,year_of_graduation',
                'providerProfile.specialties:id,provider_profile_id,specialty_name',
                'role:id,role_name',
            ],
        ],
        'patientProfile' => [
            'columns_required' => [
                'users.id',
                'users.name',
                'users.email',
                'users.role_id',
            ],
            'with_columns' => [
                'userProfile:id,user_id,name,contact_details',
                'patientProfile:id,user_id,dob,gender,blood_group,allergies',
                'role:id,role_name',
            ],
        ],
        'appointmentsDatatable' => [
            'columns_required' => [
                'appointments.id',
                'appointments.patient_id',
                'appointments.healthcare_professional_id',
                'appointments.status_id',
                'appointments.appointment_time',
            ],
            'with_columns' => [
                'patient.userProfile:id,user_id,name',
                'healthcareProfessional.userProfile:id,user_id,name',
                'status:id,status_name',

            ],
        ],
        'appointmentsDetailedDatatable' => [
            'columns_required' => [
                'appointments.id',
                'appointments.patient_id',
                'appointments.healthcare_professional_id',
                'appointments.status_id',
                'appointments.appointment_time',
                'appointments.description',
                'appointments.notes',
                'appointments.online_or_in_presence',
                'appointments.schedule_id',
                'appointments.schedule_id',
            ],
            'with_columns' => [
                'patient.userProfile:id,user_id,name',
                'healthcareProfessional.userProfile:id,user_id,name',
                'cost:id,appointment_id,amount',
                'status:id,status_name',
                'schedule:id,start_time,end_time,location_id',
                'schedule.location:id,location_name,location_address',

            ],
        ],
        'chatList' => [
            'columns_required' => [
                'messages.id',
                'messages.sender_id',
                'messages.receiver_id',
                'messages.content',
                'messages.timestamp',
            ],
            'with_columns' => [
                'sender:id,role_id,name,profile_picture',
                'sender.role:id,role_name',
                'receiver:id,role_id,name,profile_picture',
                'receiver.role:id,role_name',
            ],
        ],
        'paymentDt' => [
            'columns_required' => [
                'payments.id',
                'payments.doctor_id',
                'payments.patient_id',
                'payments.amount',
                'payments.bill_url',
                'payments.payment_time',
                'payments.cost_id',
            ],
            'with_columns' => [
                'patient:id,name',
                'doctor:id,name',
                'cost:id,appointment_id',
                'cost.appointment:id,description,online_or_in_presence,notes,schedule_id,appointment_time',
                'cost.appointment.schedule:id,location_id,start_time,end_time',
                'cost.appointment.schedule.location:id,location_name,location_address',
            ],
        ],
    ];

    /**
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function list($input)
    {
        $input = array_merge($input, $this->queryHelper[$input['type']]);

        return $this->repository->list($input);
    }

}
