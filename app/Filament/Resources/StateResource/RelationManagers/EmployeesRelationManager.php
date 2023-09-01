<?php

namespace App\Filament\Resources\StateResource\RelationManagers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('country_id')
                    ->label('Country')
                    ->options(Country::all()->pluck('name', 'id')->toArray())
                    ->reactive()->required()
                    ->afterStateUpdated(fn (callable $set) => $set('state_id', '')),

                Select::make('state_id')
                    ->label('State')
                    ->options(function (callable $get) {
                        return State::whereCountryId($get('country_id'))->get()
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->reactive()->required()
                    ->afterStateUpdated(fn (callable $set) => $set('city_id', '')),

                Select::make('city_id')
                    ->label('City')
                    ->options(function (callable $get) {
                        return City::whereStateId($get('state_id'))->get()
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->reactive()->required(),

                Select::make('department_id')->relationship('department', 'name')->required(),
                TextInput::make('firstname')->required()->maxLength(255),
                TextInput::make('lastname')->required()->maxLength(255),
                TextInput::make('address')->required()->maxLength(255),
                TextInput::make('zip_code')->required()->maxLength(7),
                DatePicker::make('birth_date')->required(),
                DatePicker::make('date_hired')->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('firstname')
            ->columns([
                TextColumn::make('index')->rowIndex()->name('id'),
                TextColumn::make('firstname')->sortable()->searchable(),
                TextColumn::make('lastname')->sortable()->searchable(),
                TextColumn::make('department.name')->sortable()->searchable(),
                TextColumn::make('address')->limit(5)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column content exceeds the length limit.
                        return $state;
                    }),
                TextColumn::make('zip_code'),
                TextColumn::make('birth_date')->date('jS F, Y'),
                TextColumn::make('date_hired')->date('jS F, Y'),
                TextColumn::make('created_at')->dateTime('jS F, Y h:ia')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
