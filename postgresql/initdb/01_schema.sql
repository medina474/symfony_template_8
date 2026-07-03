-- Symfony
-- --------------------------------------------------------------------------------
create table public.doctrine_migration_versions (
	"version" varchar(191) not null,
	executed_at timestamp(0) default null::timestamp without time zone null,
	execution_time int4 null,
	constraint doctrine_migration_versions_pkey primary key (version)
);
-- --------------------------------------------------------------------------------
