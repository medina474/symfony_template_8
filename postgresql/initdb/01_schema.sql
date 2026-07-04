-- Symfony
-- ----------------------------------------------------------------------------
create table public.doctrine_migration_versions (
    "version" varchar(191) not null,
    executed_at timestamp(0) default null::timestamp without time zone null,
    execution_time int4 null,
    constraint doctrine_migration_versions_pkey primary key (version)
);
-- ----------------------------------------------------------------------------

create table job (
    id uuid primary key default uuidv4(),
    status text not null,
    payload jsonb not null,
    result jsonb null,
    error_message text null,
    created_at timestamp(0) with time zone default current_timestamp not null,
    completed_at timestamp(0) with time zone default null::timestamp(0) with time zone
);
