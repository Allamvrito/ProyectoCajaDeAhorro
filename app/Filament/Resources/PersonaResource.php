<?php

namespace App\Filament\Resources;


use App\Filament\Resources\PersonaResource\Pages;
use App\Filament\Resources\PersonaResource\RelationManagers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteBulkAction;


use Filament\Forms; // Importa el espacio de nombres para los formularios
use Filament\Resources\Resource; // Importa la clase base Resource
use Filament\Tables; // Importa el espacio de nombres para las tablas
use App\Models\Persona; // Importa el modelo Persona
use Filament\Resources\Form; // Importa el formulario correcto
use Filament\Resources\Table; // Importa la tabla correcta


class PersonaResource extends Resource
{
    protected static ?string $model = Persona::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Gestión de Datos';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('apellido')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('numero_identidad')
                    ->required()
                    ->maxLength(15),
                Forms\Components\TextInput::make('telefono')
                    ->maxLength(15),
                Forms\Components\Textarea::make('direccion')
                    ->rows(2),
                Forms\Components\FileUpload::make('ruta_foto')
                    ->label('Foto')
                    ->image()
                    ->directory('personas')
                    ->columnSpanFull(),
                Forms\Components\Select::make('tipo')
                    ->required()
                    ->options([
                        'Socio' => 'Socio',
                        'Cliente' => 'Cliente',
                    ]),
                Forms\Components\Toggle::make('estado')
                    ->label('Activo')
                    ->default(true),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('apellido')
                    ->searchable(),
                Tables\Columns\TextColumn::make('numero_identidad'),
                Tables\Columns\TextColumn::make('telefono'),
                Tables\Columns\TextColumn::make('tipo'),
                Tables\Columns\IconColumn::make('estado')
                    ->label('Activo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('fecha_creacion')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                SelectFilter::make('estado')
                ->label('Estado')
                ->options([
                    1 => 'Activo',
                    0 => 'Inactivo',
                ])
                ->default(1), // Por defecto solo muestra activos
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                
            ])
            ->bulkActions([
                // Activar
                BulkAction::make('activar')
                    ->label('Activar')
                    ->action(fn ($records) =>
                        $records->each(fn ($record) => $record->update(['estado' => 1]))
                    )
                    ->after(fn ($records) =>
                        Notification::make()
                            ->title('Personas activadas')
                            ->body(count($records) . ' personas fueron activadas correctamente.')
                            ->success()
                            ->send()
                    )
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check-circle'),
            
                // Desactivar
                BulkAction::make('desactivar')
                    ->label('Desactivar')
                    ->action(fn ($records) =>
                        $records->each(fn ($record) => $record->update(['estado' => 0]))
                    )
                    ->after(fn ($records) =>
                        Notification::make()
                            ->title('Personas desactivadas')
                            ->body(count($records) . ' personas fueron desactivadas correctamente.')
                            ->success()
                            ->send()
                    )
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-x-circle'),
            
                // Eliminar físicamente
                DeleteBulkAction::make()
                    ->label('Eliminar')
                    ->after(fn ($records) =>
                        Notification::make()
                            ->title('Personas eliminadas')
                            ->body(count($records) . ' personas fueron eliminadas correctamente.')
                            ->success()
                            ->send()
                    )
            ]);
            
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPersonas::route('/'),
            'create' => Pages\CreatePersona::route('/create'),
            'edit' => Pages\EditPersona::route('/{record}/edit'),
        ];
    }
}
