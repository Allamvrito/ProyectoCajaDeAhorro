<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Support\Facades\Hash; // Importa la clase Hash
use Filament\Tables\Filters\SelectFilter;

use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->email()->required(),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->hiddenOn('edit'),
                Select::make('rol')
                    ->options([
                        'Administrador' => 'Administrador',
                        'Supervisor' => 'Supervisor',
                        'Usuario' => 'Usuario',
                    ])
                    ->required(),
                Toggle::make('estado')->label('Activo')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('rol'),
                IconColumn::make('estado')
                    ->boolean()
                    ->label('Activo'),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                // Filtro por estado
                SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        1 => 'Activo',
                        0 => 'Inactivo',
                    ])
                    ->default(1),
            
                SelectFilter::make('rol')
                    ->label('Rol de usuario')
                    ->options([
                        'Administrador' => 'Administrador',
                        'Supervisor' => 'Supervisor',
                        'Usuario' => 'Usuario',
                    ])
                    ->placeholder('Todos'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Activar usuarios
                BulkAction::make('activar')
                    ->label('Activar')
                    ->action(fn ($records) =>
                        $records->each(fn ($record) => $record->update(['estado' => 1]))
                    )
                    ->after(fn ($records) =>
                        Notification::make()
                            ->title('Usuarios activados')
                            ->body(count($records) . ' usuarios fueron activados correctamente.')
                            ->success()
                            ->send()
                    )
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check-circle'),
            
                // Desactivar usuarios
                BulkAction::make('desactivar')
                    ->label('Desactivar')
                    ->action(fn ($records) =>
                        $records->each(fn ($record) => $record->update(['estado' => 0]))
                    )
                    ->after(fn ($records) =>
                        Notification::make()
                            ->title('Usuarios desactivados')
                            ->body(count($records) . ' usuarios fueron desactivados correctamente.')
                            ->success()
                            ->send()
                    )
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-x-circle'),
            
                // Eliminar usuarios
                DeleteBulkAction::make()
                    ->label('Eliminar')
                    ->after(fn ($records) =>
                        Notification::make()
                            ->title('Usuarios eliminados')
                            ->body(count($records) . ' usuarios fueron eliminados correctamente.')
                            ->success()
                            ->send()
                    )
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
